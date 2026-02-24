@extends('layout')

@section('title', 'تسجيل دفعة')

@section('content')
<h1>تسجيل دفعة جديدة</h1>

<div class="card">
    <form action="{{ route('payments.store') }}" method="POST">
        @csrf
        
        <label>العميل *</label>
        <select name="client_id" required>
            <option value="">اختر عميل</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>

        <label>الصندوق *</label>
        <select name="safe_id" required>
            <option value="">اختر صندوق</option>
            @foreach($safes as $safe)
            <option value="{{ $safe->id }}">{{ $safe->name }} ({{ number_format($safe->balance, 2) }})</option>
            @endforeach
        </select>

        <label>المبلغ *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required>

        <label>النوع *</label>
        <select name="type" required>
            <option value="association">جمعية</option>
            <option value="loan">سلفة</option>
            <option value="debt">دين</option>
            <option value="deposit_return">إرجاع أمانة</option>
        </select>

        <label>تاريخ الدفع *</label>
        <input type="date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>

        <label>ملاحظات</label>
        <textarea name="notes" rows="3">{{ old('notes') }}</textarea>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('payments.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

