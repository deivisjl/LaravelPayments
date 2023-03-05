<label class="mt-3">Card details:</label>
<div class="form-group form-row">
    <div class="col-4">
        <input type="text" name="payu_card" placeholder="Card Number" class="form-control">
    </div>
    <div class="col-2">
        <input type="text" name="payu_cvc" placeholder="CVC" class="form-control">
    </div>
    <div class="col-1">
        <input type="text" name="payu_month"placeholder="MM" class="form-control">
    </div>
    <div class="col-1">
        <input type="text" name="payu_year" placeholder="YY" class="form-control">
    </div>
    <div class="col-2">
        <select name="payu_network" id="" class="custom-select">
            <option selected>Select</option>
            <option value="visa">VISA</option>
            <option value="amex">AMEX</option>
            <option value="diners">DINERS</option>
            <option value="mastercard">MASTERCARD</option>
        </select>
    </div>
</div>
<div class="form-group form-row">
    <div class="col-5">
        <input type="text" name="payu_name" placeholder="Your name" class="form-control">
    </div>
    <div class="col-5">
        <input type="text" name="payu_email" placeholder="email@example.com" class="form-control">
    </div>
</div>
<div class="form-group form-row">
    <div class="col">
        <small class="form-text text-muter" role="alert"> Your payment will be converted to {{ strtoupper(config('services.payu.base_currency')) }}</small>
    </div>
</div>
