// resources/js/razorpay-integration.js

document.addEventListener('livewire:load', function() {
    // Listen for the checkoutReady event from the Livewire component
    Livewire.on('checkoutReady', function(data) {
        initializeRazorpay(data);
    });

    // Listen for planSelected event to show visual feedback
    Livewire.on('planSelected', function(plan) {
        highlightSelectedPlan(plan);
    });
});

function initializeRazorpay(data) {
    // Make sure Razorpay SDK is loaded
    if (typeof Razorpay === 'undefined') {
        console.error('Razorpay SDK is not loaded');
        return;
    }

    var options = {
        key: data.key,
        amount: data.amount * 100, // Amount in smallest currency unit (paise)
        currency: data.currency,
        name: "Your Application Name",
        description: data.plan_name + " Plan Subscription",
        order_id: data.order_id,
        handler: function(response) {
            // Handle successful payment
            console.log("Payment successful", response);
            
            // Send payment details to the server via Livewire
            Livewire.emit('paymentSuccessful', {
                razorpay_payment_id: response.razorpay_payment_id,
                razorpay_order_id: response.razorpay_order_id,
                razorpay_signature: response.razorpay_signature,
                plan: data.plan_name
            });
        },
        prefill: {
            name: document.getElementById('user-name')?.value || "",
            email: document.getElementById('user-email')?.value || "",
            contact: document.getElementById('user-phone')?.value || ""
        },
        theme: {
            color: "#3399cc"
        },
        modal: {
            ondismiss: function() {
                console.log('Payment modal closed');
                Livewire.emit('paymentCancelled');
            }
        }
    };

    var razorpayObj = new Razorpay(options);
    razorpayObj.open();
}

function highlightSelectedPlan(plan) {
    // This function can be used for additional UI enhancements beyond Livewire's capabilities
    // For example, adding animations or transitions when a plan is selected
    console.log("Plan selected:", plan);
    
    // You could add custom animations here if needed
    // For example, using anime.js or other animation libraries
}