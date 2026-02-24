@extends('layout')

@section('title', 'العملاء المقبلين على إنهاء الجمعيات')

@section('content')
<h1>العملاء المقبلين على إنهاء الجمعيات</h1>
<a href="{{ route('reports.index') }}" class="btn">رجوع للتقارير</a>

<div class="card">
    <h2>اختر المدة</h2>
    <form method="GET">
        <label>عدد الأشهر القادمة</label>
        <input type="number" name="months" value="{{ $months }}" min="1" max="12">
        <button type="submit" class="btn btn-success">عرض</button>
    </form>
</div>

<div class="card">
    <h2>الأدوار القادمة خلال {{ $months }} شهر</h2>
    <table>
        <thead>
            <tr>
                <th>الجمعية</th>
                <th>العميل</th>
                <th>رقم الدور</th>
                <th>تاريخ الاستحقاق</th>
                <th>المبلغ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($upcoming as $member)
            <tr>
                <td><a href="{{ route('associations.show', $member->association) }}">{{ $member->association->name }}</a></td>
                <td><a href="{{ route('clients.show', $member->client) }}">{{ $member->client->name }}</a></td>
                <td>{{ $member->turn_number }}</td>
                <td>{{ $member->due_date->format('Y-m-d') }}</td>
                <td>{{ number_format($member->association->total_amount, 2) }}</td>
                <td><span class="badge badge-{{ $member->collection_status }}">{{ $member->collection_status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center;">لا توجد أدوار قادمة في هذه الفترة</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

