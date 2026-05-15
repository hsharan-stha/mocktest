@component('mail::message')
# MockTest Payment Confirmed

Dear {{ $purchase->user->name ?? 'Customer' }},

We have confirmed your payment 

Your purchase number is: **{{ $purchase->purchase_date }}**

Thank you for your payment. The mock test is now available for you.

**Amount Paid:** Rs.{{ number_format($purchase->total_amount) }}

Thank you for using our mock test service.

Regards,  
{{ config('app.name') }}
@endcomponent
