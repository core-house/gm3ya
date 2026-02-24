@extends('layout')

@section('title', 'دين #' . $debt->id)

@section('content')
<h1>دين #{{ $debt->id }}</h1>
<a href="{{ route('debts.edit', $debt) }}" class="btn">تعديل</a>
<a href="{{ route('debts.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات الدين</h2>
    <p><strong>العميل:</strong> <a href="{{ route('clients.show', $debt->client) }}">{{ $debt->client->name }}</a></p>
    <p><strong>المبلغ:</strong> {{ number_format($debt->amount, 2) }}</p>
    <p><strong>النوع:</strong> {{ $debt->type == 'temporary' ? 'مؤقت' : 'دائم' }}</p>
    <p><strong>تاريخ الاستحقاق:</strong> {{ $debt->due_date->format('Y-m-d') }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $debt->status }}">{{ $debt->status }}</span></p>
</div>
@endsection

