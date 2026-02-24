@extends('layout')

@section('title', 'المدفوعات')

@section('content')
<h1>المدفوعات</h1>
<a href="{{ route('payments.create') }}" class="btn">+ تسجيل دفعة</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>الصندوق</th>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td><a href="{{ route('clients.show', $payment->client) }}">{{ $payment->client->name }}</a></td>
                <td>{{ $payment->safe->name }}</td>
                <td><strong>{{ number_format($payment->amount, 2) }}</strong></td>
                <td><span class="badge">{{ $payment->type }}</span></td>
                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                <td>{{ $payment->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

