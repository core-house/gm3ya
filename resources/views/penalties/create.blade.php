@extends('layout')

@section('title', 'تسجيل غرامة')

@section('content')
<h1>تسجيل غرامة جديدة</h1>

<div class="card">
    <form action="{{ route('penalties.store') }}" method="POST">
        @csrf
        
        <label>العميل *</label>
        <select name="client_id" required>
            <option value="">اختر عميل</option>
            @foreach($clients as $client)
            <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>

        <label>الجمعية (اختياري)</label>
        <select name="association_id">
            <option value="">لا توجد</option>
            @foreach($associations as $association)
            <option value="{{ $association->id }}">{{ $association->name }}</option>
            @endforeach
        </select>

        <label>المبلغ *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required>

        <label>النوع *</label>
        <select name="type" required>
            <option value="late_payment">تأخير في الدفع</option>
            <option value="early_exit">خروج مبكر من الجمعية</option>
            <option value="breach">مخالفة</option>
            <option value="other">أخرى</option>
        </select>

        <label>السبب *</label>
        <textarea name="reason" rows="3" required>{{ old('reason') }}</textarea>

        <label>تاريخ الغرامة *</label>
        <input type="date" name="penalty_date" value="{{ old('penalty_date', date('Y-m-d')) }}" required>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('penalties.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

