@extends('layout')

@section('title', 'غرامة #' . $penalty->id)

@section('content')
<h1>غرامة #{{ $penalty->id }}</h1>
<a href="{{ route('penalties.edit', $penalty) }}" class="btn">تعديل</a>
<a href="{{ route('penalties.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات الغرامة</h2>
    <p><strong>العميل:</strong> <a href="{{ route('clients.show', $penalty->client) }}">{{ $penalty->client->name }}</a></p>
    @if($penalty->association)
    <p><strong>الجمعية:</strong> <a href="{{ route('associations.show', $penalty->association) }}">{{ $penalty->association->name }}</a></p>
    @endif
    <p><strong>المبلغ:</strong> {{ number_format($penalty->amount, 2) }}</p>
    <p><strong>النوع:</strong>
        @if($penalty->type == 'late_payment') تأخير في الدفع
        @elseif($penalty->type == 'early_exit') خروج مبكر من الجمعية
        @elseif($penalty->type == 'breach') مخالفة
        @else أخرى
        @endif
    </p>
    <p><strong>السبب:</strong> {{ $penalty->reason }}</p>
    <p><strong>تاريخ الغرامة:</strong> {{ $penalty->penalty_date->format('Y-m-d') }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $penalty->status }}">{{ $penalty->status }}</span></p>
    @if($penalty->paid_date)
    <p><strong>تاريخ السداد:</strong> {{ $penalty->paid_date->format('Y-m-d') }}</p>
    @endif
</div>

@if($penalty->status == 'pending')
<div class="card">
    <h2>إجراءات</h2>
    <form action="{{ route('penalties.markPaid', $penalty) }}" method="POST" style="display:inline;">
        @csrf
        <button class="btn btn-success">تسجيل السداد</button>
    </form>
    <form action="{{ route('penalties.waive', $penalty) }}" method="POST" style="display:inline;">
        @csrf
        <button class="btn btn-danger" onclick="return confirm('متأكد من إلغاء الغرامة؟')">إلغاء الغرامة</button>
    </form>
</div>
@endif
@endsection

