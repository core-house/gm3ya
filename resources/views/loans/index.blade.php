@extends('layout')

@section('title', 'السلف')

@section('content')
<h1>السلف</h1>
<a href="{{ route('loans.create') }}" class="btn">+ إضافة سلفة</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>الصندوق</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loans as $loan)
            <tr>
                <td>{{ $loan->id }}</td>
                <td><a href="{{ route('clients.show', $loan->client) }}">{{ $loan->client->name }}</a></td>
                <td>{{ $loan->safe->name }}</td>
                <td>{{ number_format($loan->amount, 2) }}</td>
                <td>{{ $loan->loan_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
                <td>
                    <a href="{{ route('loans.show', $loan) }}" class="btn">عرض</a>
                    <a href="{{ route('loans.edit', $loan) }}" class="btn">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

