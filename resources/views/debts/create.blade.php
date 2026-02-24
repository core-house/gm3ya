@extends('layout')

@section('title', 'إضافة دين')

@section('content')
<h1>إضافة دين جديد</h1>

<div class="card">
    <form action="{{ route('debts.store') }}" method="POST">
        @csrf
        
        <label>العميل *</label>
        <select name="client_id" required>
            <option value="">اختر عميل</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>

        <label>المبلغ *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required>

        <label>النوع *</label>
        <select name="type" required>
            <option value="temporary">مؤقت</option>
            <option value="permanent">دائم</option>
        </select>

        <label>تاريخ الاستحقاق *</label>
        <input type="date" name="due_date" value="{{ old('due_date') }}" required>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('debts.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

