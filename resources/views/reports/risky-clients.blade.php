@extends('layout')

@section('title', 'العملاء عالي المخاطر')

@section('content')
<h1>تقرير العملاء عالي المخاطر</h1>
<a href="{{ route('reports.index') }}" class="btn">رجوع للتقارير</a>

<div class="card">
    <h2>العملاء المتعثرين ({{ $risky_clients->count() }})</h2>
    <table>
        <thead>
            <tr>
                <th>العميل</th>
                <th>ديون معلقة</th>
                <th>غرامات معلقة</th>
                <th>سلف نشطة</th>
                <th>الإجمالي</th>
                <th>الكفيل</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($risky_clients as $client)
            @php
                $total_debts = $client->debts->where('status', 'pending')->sum('amount');
                $total_penalties = $client->penalties->where('status', 'pending')->sum('amount');
                $total_loans = $client->loans->where('status', 'active')->sum('amount');
                $total_risk = $total_debts + $total_penalties + $total_loans;
            @endphp
            <tr style="background: {{ $total_risk > 5000 ? '#ffcccc' : '#ffffcc' }};">
                <td>
                    <a href="{{ route('clients.show', $client) }}">{{ $client->name }}</a>
                    <br><small>{{ $client->phone }}</small>
                </td>
                <td>{{ number_format($total_debts, 2) }}</td>
                <td>{{ number_format($total_penalties, 2) }}</td>
                <td>{{ number_format($total_loans, 2) }}</td>
                <td><strong>{{ number_format($total_risk, 2) }}</strong></td>
                <td>
                    @if($client->guarantor_name)
                        {{ $client->guarantor_name }}
                        <br><small>{{ $client->guarantor_phone }}</small>
                    @elseif($client->guarantor)
                        <a href="{{ route('clients.show', $client->guarantor) }}">{{ $client->guarantor->name }}</a>
                    @else
                        <span style="color: #f00;">لا يوجد</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('clients.show', $client) }}" class="btn">عرض</a>
                    @if($client->guarantor_phone)
                        <span class="badge" style="background: #0af;">{{ $client->guarantor_phone }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align: center; background: #0f0;">✓ لا يوجد عملاء عالي المخاطر حالياً</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($risky_clients->count() > 0)
<div class="card">
    <h2>الإجراءات المقترحة</h2>
    <ul style="margin-right: 20px; line-height: 2;">
        <li><strong>التواصل الفوري:</strong> الاتصال بالعملاء المتعثرين لمعرفة الأسباب</li>
        <li><strong>التواصل مع الكفلاء:</strong> إخطار الكفلاء بالمبالغ المستحقة</li>
        <li><strong>جدولة السداد:</strong> عمل خطة سداد مرنة مع العملاء</li>
        <li><strong>الغرامات:</strong> تطبيق الغرامات حسب الاتفاق</li>
        <li><strong>الإجراءات القانونية:</strong> في حالة استمرار التعثر</li>
        <li><strong>منع منح سلف جديدة:</strong> للعملاء المتعثرين</li>
    </ul>
</div>
@endif
@endsection

