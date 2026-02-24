@extends('layout')

@section('title', 'العملاء')

@section('content')
<h1>العملاء</h1>
<a href="{{ route('clients.create') }}" class="btn">+ إضافة عميل</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الهاتف</th>
                <th>التقييم</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td><a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a></td>
                <td>{{ $client->phone }}</td>
                <td>{{ $client->rate }}</td>
                <td><span class="badge badge-{{ $client->status }}">{{ $client->status }}</span></td>
                <td>
                    <a href="{{ route('clients.edit', $client) }}" class="btn">تعديل</a>
                    <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('متأكد؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

