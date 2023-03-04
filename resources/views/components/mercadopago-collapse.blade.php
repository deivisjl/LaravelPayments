<label class="mt-3">Card details:</label>
<div class="form-group form-row">
    <div class="col-5">
        <input type="text" placeholder="Card Number" id="cardNumber" data-checkout="cardNumber" class="form-control">
    </div>
</div>
<div class="form-group form-row">
    <input type="text" placeholder="CVC" data-checkout="securitycode" class="form-control">
</div>
<div class="col-1"></div>
<div class="col-1">
    <input type="text" placeholder="MM" data-checkout="carExpirationMonth" class="form-control">
</div>
<div class="col-1">
    <input type="text" placeholder="YY" data-checkout="cardExpirationYear" class="form-control">
</div>
<div class="form-group form-row">
    <div class="col-5">
        <input type="text" placeholder="Your name" data-checkout="cardholderName" class="form-control">
    </div>
    <div class="col-5">
        <input type="text" placeholder="email@example.com" data-checkout="cardholderEmail" class="form-control" name="email">
    </div>
</div>
<div class="form-group form-row">
    <div class="col-2">
        <select class="custom-select" data-checkout="docType"></select>
    </div>
    <div class="col-3">
        <input type="text" class="form-control" data-checkout="docNumber" placeholder="Document">
    </div>
</div>
<div class="form-group form-row">
    <div class="col">
        <small class="form-text text-muter" role="alert"> Your payment will be converted to {{ strtoupper(config('services.mercadopago.base_currency')) }}</small>
    </div>
</div>
<div class="form-group form-row">
    <div class="col">
        <small class="form-text text-danger" id="paymentErrors" role="alert"></small>
    </div>
</div>
<input type="hidden" id="cardNetwork" name="card_network">
<input type="hidden" id="cardToken" name="card_token">

@push('scripts')
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script>
    const mercadoPago = windows.MercadoPago;

    mercadoPago.setPublishableKey('{{ config('services.mercadopago.key') }}');

    mercadoPago.GetIdentificationTypes();
</script>
<script>
    function setCardNetwork()
    {
        const cardNumber = document.getElementById("cardNumber");

        mercadoPago.getPaymentMethod(
            { "bin": cardNumber.value.substring(0,6) },
            function(status, response){
                const cardNetwork = document.getElementById("cardNetwork");

                cardNetwork.value = response[0].id;
            });
    }
</script>
<script>
    const mercadopagoForm = document.getElementById("paymentForm");

    mercadopagoForm.addEventListener('submit', function(e){

        if(form.elements.payment_platform.value === "{{ $paymentPlatform->id }}"){
            e.preventDefault();

            mercadoPago.createToken(mercadoPagoForm, function(status, response){
                if(status != 200 && status != 201){
                    const errors = document.getElementById("paymentErrors");

                    errors.textContent = response.cause[0].description;
                }
                else{
                    const cardToken = document.getElementById("cardToken");

                    setCardNetwork();

                    cardToken.value = response.id;

                    mercadopagoForm.submit();
                }
            });
        }
    });
</script>
@endpush
