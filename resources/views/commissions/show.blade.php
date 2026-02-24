@extends('layout')

@section('title', 'عمولة #' . $commission->id)

@section('content')
<h1>عمولة #{{ $commission->id }}</h1>
<a href="{{ route('commissions.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات العمولة</h2>
    <p><strong>الجمعية:</strong> <a href="{{ route('associations.show', $commission->association) }}">{{ $commission->association->name }}</a></p>
    <p><strong>الصندوق:</strong> {{ $commission->safe->name }}</p>
    <p><strong>المبلغ:</strong> {{ number_format($commission->amount, 2) }}</p>
    <p><strong>النوع:</strong> {{ $commission->type == 'percentage' ? 'نسبة' : 'ثابت' }}</p>
    <p><strong>التاريخ:</strong> {{ $commission->commission_date->format('Y-m-d') }}</p>
    <p><strong>ملاحظات:</strong> {{ $commission->notes }}</p>
</div>
@endsection

