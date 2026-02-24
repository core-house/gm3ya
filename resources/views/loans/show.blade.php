@extends('layout')

@section('title', 'سلفة #' . $loan->id)

@section('content')
<h1>سلفة #{{ $loan->id }}</h1>
<a href="{{ route('loans.edit', $loan) }}" class="btn">تعديل</a>
<a href="{{ route('loans.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات السلفة</h2>
    <p><strong>العميل:</strong> <a href="{{ route('clients.show', $loan->client) }}">{{ $loan->client->name }}</a></p>
    <p><strong>الصندوق:</strong> {{ $loan->safe->name }}</p>
    <p><strong>المبلغ:</strong> {{ number_format($loan->amount, 2) }}</p>
    <p><strong>التاريخ:</strong> {{ $loan->loan_date->format('Y-m-d') }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></p>
</div>
@endsection

