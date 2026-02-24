@extends('layout')

@section('title', 'تعديل سلفة')

@section('content')
<h1>تعديل سلفة #{{ $loan->id }}</h1>

<div class="card">
    <form action="{{ route('loans.update', $loan) }}" method="POST">
        @csrf @method('PUT')
        
        <p><strong>العميل:</strong> {{ $loan->client->name }}</p>
        <p><strong>المبلغ:</strong> {{ number_format($loan->amount, 2) }}</p>
        <p><strong>التاريخ:</strong> {{ $loan->loan_date->format('Y-m-d') }}</p>

        <label>الحالة</label>
        <select name="status">
            <option value="active" {{ $loan->status == 'active' ? 'selected' : '' }}>نشط</option>
            <option value="paid" {{ $loan->status == 'paid' ? 'selected' : '' }}>مسددة</option>
        </select>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('loans.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

