@extends('layout')

@section('title', 'تسجيل عمولة')

@section('content')
<h1>تسجيل عمولة جديدة</h1>

<div class="card">
    <form action="{{ route('commissions.store') }}" method="POST">
        @csrf
        
        <label>الجمعية *</label>
        <select name="association_id" required>
            <option value="">اختر جمعية</option>
            @foreach($associations as $association)
            <option value="{{ $association->id }}">{{ $association->name }}</option>
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
            <option value="fixed">ثابت</option>
            <option value="percentage">نسبة</option>
        </select>

        <label>تاريخ العمولة *</label>
        <input type="date" name="commission_date" value="{{ old('commission_date', date('Y-m-d')) }}" required>

        <label>ملاحظات</label>
        <textarea name="notes" rows="3">{{ old('notes') }}</textarea>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('commissions.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

