@extends('layout')

@section('title', 'المقبوضات')

@section('content')
<h1>المقبوضات</h1>
<a href="{{ route('receipts.create') }}" class="btn">+ تسجيل قبض</a>

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
            @foreach($receipts as $receipt)
            <tr>
                <td>{{ $receipt->id }}</td>
                <td><a href="{{ route('clients.show', $receipt->client) }}">{{ $receipt->client->name }}</a></td>
                <td>{{ $receipt->safe->name }}</td>
                <td><strong>{{ number_format($receipt->amount, 2) }}</strong></td>
                <td><span class="badge">{{ $receipt->type }}</span></td>
                <td>{{ $receipt->receipt_date->format('Y-m-d') }}</td>
                <td>{{ $receipt->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

