@extends('layout')

@section('title', 'الغرامات')

@section('content')
<h1>الغرامات</h1>
<a href="{{ route('penalties.create') }}" class="btn">+ تسجيل غرامة</a>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>العميل</th>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penalties as $penalty)
            <tr>
                <td>{{ $penalty->id }}</td>
                <td><a href="{{ route('clients.show', $penalty->client) }}">{{ $penalty->client->name }}</a></td>
                <td><strong>{{ number_format($penalty->amount, 2) }}</strong></td>
                <td><span class="badge">
                    @if($penalty->type == 'late_payment') تأخير دفع
                    @elseif($penalty->type == 'early_exit') خروج مبكر
                    @elseif($penalty->type == 'breach') مخالفة
                    @else أخرى
                    @endif
                </span></td>
                <td>{{ $penalty->penalty_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $penalty->status }}">{{ $penalty->status }}</span></td>
                <td>
                    <a href="{{ route('penalties.show', $penalty) }}" class="btn">عرض</a>
                    @if($penalty->status == 'pending')
                        <form action="{{ route('penalties.markPaid', $penalty) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-success">سدد</button>
                        </form>
                        <form action="{{ route('penalties.waive', $penalty) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn" onclick="return confirm('متأكد من إلغاء الغرامة؟')">إلغاء</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

