@extends('layout')

@section('title', 'عميل: ' . $client->name)

@section('content')
<h1>{{ $client->name }}</h1>
<a href="{{ route('clients.edit', $client) }}" class="btn">تعديل</a>
<a href="{{ route('clients.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات العميل</h2>
    <p><strong>الهاتف:</strong> {{ $client->phone }}</p>
    <p><strong>العنوان:</strong> {{ $client->address }}</p>
    <p><strong>الرقم القومي:</strong> {{ $client->national_id }}</p>
    <p><strong>جهة العمل:</strong> {{ $client->work_place }}</p>
    <p><strong>المرتب:</strong> {{ $client->salary ? number_format($client->salary, 2) : '-' }}</p>
    <p><strong>التقييم:</strong> {{ $client->rate }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $client->status }}">{{ $client->status }}</span></p>
</div>

@if($client->guarantor_name || $client->guarantor)
<div class="card">
    <h2>معلومات الكفيل</h2>
    @if($client->guarantor)
        <p><strong>الكفيل:</strong> <a href="{{ route('clients.show', $client->guarantor) }}">{{ $client->guarantor->name }}</a></p>
    @else
        <p><strong>اسم الكفيل:</strong> {{ $client->guarantor_name }}</p>
        <p><strong>هاتف الكفيل:</strong> {{ $client->guarantor_phone }}</p>
        <p><strong>الرقم القومي:</strong> {{ $client->guarantor_national_id }}</p>
        <p><strong>عنوان الكفيل:</strong> {{ $client->guarantor_address }}</p>
    @endif
</div>
@endif

@if($client->guaranteedClients->count() > 0)
<div class="card">
    <h2>العملاء المكفولين من هذا العميل</h2>
    <ul>
        @foreach($client->guaranteedClients as $guaranteed)
            <li><a href="{{ route('clients.show', $guaranteed) }}">{{ $guaranteed->name }}</a></li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">
    <h2>الجمعيات</h2>
    <table>
        <thead>
            <tr>
                <th>الجمعية</th>
                <th>رقم الدور</th>
                <th>تاريخ الاستحقاق</th>
                <th>حالة القبض</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->associationMembers as $member)
            <tr>
                <td><a href="{{ route('associations.show', $member->association) }}">{{ $member->association->name }}</a></td>
                <td>{{ $member->turn_number }}</td>
                <td>{{ $member->due_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $member->collection_status }}">{{ $member->collection_status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">لا توجد جمعيات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>السلف</h2>
    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->loans as $loan)
            <tr>
                <td>{{ number_format($loan->amount, 2) }}</td>
                <td>{{ $loan->loan_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $loan->status }}">{{ $loan->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align: center;">لا توجد سلف</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>الديون</h2>
    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>تاريخ الاستحقاق</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->debts as $debt)
            <tr>
                <td>{{ number_format($debt->amount, 2) }}</td>
                <td>{{ $debt->type }}</td>
                <td>{{ $debt->due_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $debt->status }}">{{ $debt->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">لا توجد ديون</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="card">
    <h2>الغرامات</h2>
    <table>
        <thead>
            <tr>
                <th>المبلغ</th>
                <th>النوع</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($client->penalties as $penalty)
            <tr>
                <td>{{ number_format($penalty->amount, 2) }}</td>
                <td>{{ $penalty->type }}</td>
                <td>{{ $penalty->penalty_date->format('Y-m-d') }}</td>
                <td><span class="badge badge-{{ $penalty->status }}">{{ $penalty->status }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align: center;">لا توجد غرامات</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

