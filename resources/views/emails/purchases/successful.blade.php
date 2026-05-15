@component('mail::message')
# Japanese Mock Test Bank Transfer Information

Dear {{ $purchase->user->name ?? 'Customer' }},

Thank you very much for purchasing mock test.

**Purchase Number:** {{ $purchase->purchase_date }}  
**Total Amount:** Rs.{{ number_format($purchase->total_amount) }}  
**Items Purchased:** {{ $purchase->item_count }}

@component('mail::table')
| Book Title      | Quantity  | Unit Price |
| --------------- | --------- | ---------- |
@foreach ($purchase->details as $detail)
| {{ $detail->book->name ?? 'Unknown' }} | {{ $detail->quantity }} | ¥{{ number_format($detail->price) }} |
@endforeach
@endcomponent

---

**Please transfer the total amount to the following bank account:**

@component('mail::table')
| Account Details  | Information             |
| ---------------- | ----------------------- |
| Bank Name        | XXXXXXX      |
| Branch Name      | XXXXXXX            |
| Account Type     | XXXXXXX                |
| Account Number   | XXXXXXX            |
| Account Holder   | XXXXXXX |
@endcomponent

---

Thank you for your prompt payment.  
We appreciate your business.

Best regards,  
{{ config('app.name') }}
@endcomponent
