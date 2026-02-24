@extends('layout')

@section('title', 'الأمانات')

@section('content')
<h1>الأمانات</h1>
<a href="{{ route('deposits.create') }}" class="btn">+ إضافة أمانة</a>

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
            @foreach($deposits as $deposit)
            <tr>
                <td>{{ $deposit->id }}</td>
                <td><a href="{{ route('clients.show', $deposit->client) }}">{{ $deposit->client->name }}</a></td>
                <td>{{ $deposit->safe->name }}</td>
                <td>{{ number_format($deposit->amount, 2) }}</td>
                <td>{{ $deposit->deposit_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $deposit->status }}">{{ $deposit->status }}</span></td>
                <td>
                    <a href="{{ route('deposits.show', $deposit) }}" class="btn">عرض</a>
                    <a href="{{ route('deposits.edit', $deposit) }}" class="btn">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

