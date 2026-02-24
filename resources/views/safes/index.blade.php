@extends('layout')

@section('title', 'الصناديق')

@section('content')
<h1>الصناديق</h1>
<a href="{{ route('safes.create') }}" class="btn">+ إضافة صندوق</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الرصيد</th>
                <th>الوصف</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($safes as $safe)
            <tr>
                <td>{{ $safe->id }}</td>
                <td><a href="{{ route('safes.show', $safe) }}">{{ $safe->name }}</a></td>
                <td><strong>{{ number_format($safe->balance, 2) }}</strong></td>
                <td>{{ $safe->description }}</td>
                <td>
                    <a href="{{ route('safes.show', $safe) }}" class="btn">عرض</a>
                    <a href="{{ route('safes.edit', $safe) }}" class="btn">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

