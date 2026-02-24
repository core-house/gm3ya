@extends('layout')

@section('title', 'العمولات')

@section('content')
<h1>العمولات</h1>
<a href="{{ route('commissions.create') }}" class="btn">+ تسجيل عمولة</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>الجمعية</th>
                <th>الصندوق</th>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commissions as $commission)
            <tr>
                <td>{{ $commission->id }}</td>
                <td><a href="{{ route('associations.show', $commission->association) }}">{{ $commission->association->name }}</a></td>
                <td>{{ $commission->safe->name }}</td>
                <td><strong>{{ number_format($commission->amount, 2) }}</strong></td>
                <td><span class="badge">{{ $commission->type == 'percentage' ? 'نسبة' : 'ثابت' }}</span></td>
                <td>{{ $commission->commission_date->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('commissions.show', $commission) }}" class="btn">عرض</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

