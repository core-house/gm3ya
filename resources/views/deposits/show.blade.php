@extends('layout')

@section('title', 'أمانة #' . $deposit->id)

@section('content')
<h1>أمانة #{{ $deposit->id }}</h1>
<a href="{{ route('deposits.edit', $deposit) }}" class="btn">تعديل</a>
<a href="{{ route('deposits.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات الأمانة</h2>
    <p><strong>العميل:</strong> <a href="{{ route('clients.show', $deposit->client) }}">{{ $deposit->client->name }}</a></p>
    <p><strong>الصندوق:</strong> {{ $deposit->safe->name }}</p>
    <p><strong>المبلغ:</strong> {{ number_format($deposit->amount, 2) }}</p>
    <p><strong>التاريخ:</strong> {{ $deposit->deposit_date->format('Y-m-d') }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $deposit->status }}">{{ $deposit->status }}</span></p>
</div>
@endsection

