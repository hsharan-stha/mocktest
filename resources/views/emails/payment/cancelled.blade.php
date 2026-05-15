@component('mail::message')
# Payment Cancelled

Dear {{ $purchase->user->name ?? 'Customer' }},

We regret to inform you that the payment for **#{{ $purchase->purchase_date }}** has been cancelled.

**Amount:** ¥{{ number_format($purchase->total_amount) }}

@if($purchase->details)
@component('mail::table')
| Book Title      | Quantity  | Unit Price |
| --------------- | --------- | ---------- |
@foreach ($purchase->details as $detail)
| {{ $detail->book->name ?? 'Unknown' }} | {{ $detail->quantity }} | ¥{{ number_format($detail->price) }} |
@endforeach
@endcomponent
@endif

If this was a mistake or if you need assistance, please contact our support team.

Thanks,  
{{ config('app.name') }}
@endcomponent
