@push('styles')

@endpush
<label class="mt-3">Card details:</label>
<div id="cardElement"></div>
<small class="form-text text-muted" id="cardErrors" role="alert"></small>
<input type="hidden" name="payment_method" id="paymentMethod">

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}')

    const elements = stripe.elements({locale:'en'});
    const cardElement = elements.create('card');

    cardElement.mount(#cardElement);
</script>
<script>
    const form = document.getElementById('paymentForm');
    const payButton = document.getElementById('payButton');

    payButton.addEventListener('click', async(e) =>{
        if(form.element.payment_platform.value === "{{ $paymentPlatform->id }}")
        {
            e.preventDefault();

            const {paymentMethod, error} = await stripe.createPaymentMethod(
                'card',cardElement,{
                    billing_details:{
                        "name":"{{ auth()->user()->name }}",
                        "email":"{{ auth()->user()->email }}"
                    }
                }
            );

            if(error){
                const displayError = document.getElementById('cardErrors');
                displayError.textContent = error.Message;
            }else{
                const tokenInput = document.getElementById('paymentMethod');
                tokenInput.value = paymentMethod.id;
                form.submit();
            }
        }
    });
</script>
@endpush
