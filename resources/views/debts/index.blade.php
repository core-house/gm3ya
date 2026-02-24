@extends('layout')

@section('title', 'الديون')

@section('content')
<h1>الديون</h1>
<a href="{{ route('debts.create') }}" class="btn">+ إضافة دين</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($debts as $debt)
            <tr>
                <td>{{ $debt->id }}</td>
                <td><a href="{{ route('clients.show', $debt->client) }}">{{ $debt->client->name }}</a></td>
                <td>{{ number_format($debt->amount, 2) }}</td>
                <td><span class="badge">{{ $debt->type == 'temporary' ? 'مؤقت' : 'دائم' }}</span></td>
                <td>{{ $debt->due_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $debt->status }}">{{ $debt->status }}</span></td>
                <td>
                    <a href="{{ route('debts.show', $debt) }}" class="btn">عرض</a>
                    <a href="{{ route('debts.edit', $debt) }}" class="btn">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

