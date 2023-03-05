<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ConsumesExternalServices;
use App\Services\CurrencyConversionService;

class PayuService
{
    use ConsumesExternalServices;

    protected $base_uri;
    protected $account_id;
    protected $merchant_id;
    protected $key;
    protected $secret;
    protected $base_currency;
    protected $converter;

    public function __construct(CurrencyConversionService $converter)
    {
        $this->base_uri = config('services.payu.base_uri');
        $this->account_id = config('services.payu.account_id');
        $this->merchant_id = config('services.payu.merchant_id');
        $this->key = config('services.payu.key');
        $this->secret = config('services.payu.secret');
        $this->base_currency = strtoupper(config('services.payu.base_currency'));
        $this->converter = $converter;
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $formParams['merchant']['apiKey'] = $this->key;
        $formParams['merchant']['apiLogin'] = $this->secret;
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {

    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            'payu_card' => 'required',
            'payu_cvc' => 'required',
            'payu_year' => 'required',
            'payu_month' => 'required',
            'payu_network' => 'required',
            'payu_name' => 'required',
            'payu_email' => 'required',
        ]);

        $payment = $this->createPayment(
            $request->value,
            $request->currency,
            $request->payu_name,
            $request->payu_email,
            $request->payu_car,
            $request->payu_cvc,
            $request->payu_year,
            $request->payu_month,
            $request->payu_network,
        );

        if($payment->transactionResponse->state === "APPROVED"){

            $name = $request->payu_name;

            $amount = $request->value;
            $currency = strtoupper($request->currency);

            return redirect()
            ->route('home')
            ->withSuccess(['payment' => "Thanks, {$name}. We received your {$amount}{$currency} payment."]);
        }

        return redirect()
            ->route('home')
            ->withErrors('We were unable to proccess your payment. Check your details and try again, please');
    }

    public function handleApproval()
    {

    }

    public function createPayment($value, $currency, $name, $email, $card, $cvc, $year, $month, $network, $installments = 1, $paymentCountry = 'CO')
    {
        return $this->makeRequest(
            'POST',
            '/payments-api/4.0/service.cgi',
            [],
            [
                'language' => $language = config('app.locale'),
                'command' => 'SUBMIT_TRANSACTION',
                'test' => false,
                'transaction' => [
                    'type' => 'AUTHORIZATION_AND_CAPTURE',
                    'paymentMethod' => strtoupper($network),
                    'paymentCountry' => strtoupper($paymentCountry),
                    'deviceSessionId' => session()->getId(),
                    'ipAddress' => request()->ip(),
                    'userAgent' => request()->header('User-Agent'),
                    'creditCard' => [
                        'number' => $card,
                        'securityCode' => $cvc,
                        'expirationDate' => "{$year}/{$month}",
                        'name' => "APPROVED",
                    ],
                    'extraParameters' => [
                        'INSTALLMENTS_NUMBER'=>$installments,
                    ],
                    'payer' =>[
                        'fullName' => $name,
                        'emailAddress' => $email,
                    ],
                    'order' => [
                        'accountId' => $this->account_id,
                        'referenceCode' => $reference = Str::random(12),
                        'description' => 'Testing PayU',
                        'language' => $language,
                        'signature' => $this->generateSignature($reference,$value = round($value * $this->resolveFactor($currency))),
                        'additionalValue' => [
                            'TX_VALUE' => [
                                'value'=>$value,
                                'currency' => $this->base_currency,
                            ],
                        ],
                        'buyer' =>[
                            'fullName' => $name,
                            'emailAddress' => $email,
                            'shippingAddress' => [
                                'street1' => '',
                                'city' => '',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'Accept' => 'application/json'
            ],
            $isJsonRequest = true,
        );
    }

    public function resolveFactor($currency)
    {
        return $this->converter
            ->convertCurrency($currency, $this->base_currency);
    }

    public function generateSignature($referenceCode, $value)
    {
        return md5("{$this->key}~{$this->merchant_id}~{$referenceCode}~{$value}~{$this->base_currency}");
    }
}
