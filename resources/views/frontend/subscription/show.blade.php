@extends('layouts.app')

@section('content')
<div class="container min-vh-100 pt-5 text-white" style="background-color: #121212;">
    <div class="row justify-content-center mt-5 pt-5">
        <!-- Card Payment -->
        <div class="col-md-6 mb-4">
            <div class="card bg-dark text-white p-4 shadow rounded">
                <h3 class="mb-3 text-center">Subscribe with Card</h3>
                <p class="text-center mb-4">Get unlimited daily motivational audios tailored for you.</p>

                <form action="{{ route('subscription.checkout') }}" method="POST">
                    @csrf
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">
                            Subscribe for &pound;9.99/month
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- PayPal -->
        <div class="col-md-6 mb-4 d-none">
            <div class="card bg-dark text-white p-4 shadow rounded">
                <h3 class="mb-3 text-center">Subscribe with PayPal</h3>
                <p class="text-center mb-4">Use your PayPal account to subscribe instantly.</p>

                <div id="paypal-button-container-P-3FT002174V252551DNCL32NA" class="d-grid"></div>
            </div>
        </div>
    </div>
</div>

<!-- PayPal SDK -->
<!-- <script src="https://www.paypal.com/sdk/js?client-id=AfDByHi21f0F_LE1hb6O1ygtwaiwxuFlUvP1lwrZSlR3AeSxRV80vssVyqI3w30I2OkZA7TvfcHPsFjY&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script> -->

<!-- <script>
paypal.Buttons({
    style: {
        shape: 'rect',
        color: 'gold',
        layout: 'vertical',
        label: 'subscribe'
    },
    createSubscription: function(data, actions) {
        return actions.subscription.create({
            plan_id: 'P-3FT002174V252551DNCL32NA' // PayPal plan ID
        });
    },
    onApprove: function(data, actions) {
        // You can send subscriptionID to your backend here
        alert("Subscription successful! ID: " + data.subscriptionID);
    }
}).render('#paypal-button-container-P-3FT002174V252551DNCL32NA');
</script> -->
@endsection
