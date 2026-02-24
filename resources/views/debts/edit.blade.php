@extends('layout')

@section('title', 'تعديل دين')

@section('content')
<h1>تعديل دين #{{ $debt->id }}</h1>

<div class="card">
    <form action="{{ route('debts.update', $debt) }}" method="POST">
        @csrf @method('PUT')
        
        <label>المبلغ *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount', $debt->amount) }}" required>

        <label>النوع *</label>
        <select name="type" required>
            <option value="temporary" {{ $debt->type == 'temporary' ? 'selected' : '' }}>مؤقت</option>
            <option value="permanent" {{ $debt->type == 'permanent' ? 'selected' : '' }}>دائم</option>
        </select>

        <label>تاريخ الاستحقاق *</label>
        <input type="date" name="due_date" value="{{ old('due_date', $debt->due_date->format('Y-m-d')) }}" required>

        <label>الحالة</label>
        <select name="status">
            <option value="pending" {{ $debt->status == 'pending' ? 'selected' : '' }}>معلق</option>
            <option value="paid" {{ $debt->status == 'paid' ? 'selected' : '' }}>مسدد</option>
        </select>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('debts.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

