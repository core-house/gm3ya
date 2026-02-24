@extends('layout')

@section('title', 'إضافة سلفة')

@section('content')
<h1>إضافة سلفة جديدة</h1>

<div class="card">
    <form action="{{ route('loans.store') }}" method="POST">
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

        <label>تاريخ السلفة *</label>
        <input type="date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('loans.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

