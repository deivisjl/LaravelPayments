<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class MercadoPagoService
{
    use ConsumesExternalServices;

    protected $base_uri;
    protected $key;
    protected $secret;
    protected $base_currency;

    public function __construct()
    {
        $this->base_uri = config('services.mercadopago.base_uri');
        $this->key = config('services.mercadopago.key');
        $this->secret = config('services.mercadopago.secret');
        $this->base_currency = config('services.mercadopago.base_currency');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {

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
        dd($request->all());
    }

    public function handleApproval()
    {

    }

    public function resolveFactor($currency)
    {

    }
}
