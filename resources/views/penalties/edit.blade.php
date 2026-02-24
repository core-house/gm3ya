@extends('layout')

@section('title', 'تعديل غرامة')

@section('content')
<h1>تعديل غرامة #{{ $penalty->id }}</h1>

<div class="card">
    <form action="{{ route('penalties.update', $penalty) }}" method="POST">
        @csrf @method('PUT')
        
        <p><strong>العميل:</strong> {{ $penalty->client->name }}</p>

        <label>المبلغ *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $penalty->amount) }}" required>

        <label>النوع *</label>
        <select name="type" required>
            <option value="late_payment" {{ $penalty->type == 'late_payment' ? 'selected' : '' }}>تأخير في الدفع</option>
            <option value="early_exit" {{ $penalty->type == 'early_exit' ? 'selected' : '' }}>خروج مبكر من الجمعية</option>
            <option value="breach" {{ $penalty->type == 'breach' ? 'selected' : '' }}>مخالفة</option>
            <option value="other" {{ $penalty->type == 'other' ? 'selected' : '' }}>أخرى</option>
        </select>

        <label>السبب *</label>
        <textarea name="reason" rows="3" required>{{ old('reason', $penalty->reason) }}</textarea>

        <label>الحالة</label>
        <select name="status">
            <option value="pending" {{ $penalty->status == 'pending' ? 'selected' : '' }}>معلق</option>
            <option value="paid" {{ $penalty->status == 'paid' ? 'selected' : '' }}>مدفوع</option>
            <option value="waived" {{ $penalty->status == 'waived' ? 'selected' : '' }}>ملغي</option>
        </select>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('penalties.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

