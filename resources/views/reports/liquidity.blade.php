@extends('layout')

@section('title', 'تقرير السيولة')

@section('content')
<h1>تقرير السيولة والمخاطر المالية</h1>
<a href="{{ route('reports.index') }}" class="btn">رجوع للتقارير</a>

<div class="stats">
    <div class="stat-box" style="background: #0f0;">
        <h3>{{ number_format($actual_cash, 2) }}</h3>
        <p>الرصيد الفعلي (الكاش)</p>
    </div>
    <div class="stat-box" style="background: #f00; color: #fff;">
        <h3>{{ number_format($upcoming_obligations, 2) }}</h3>
        <p>الأدوار المستحقة علينا</p>
    </div>
    <div class="stat-box" style="background: #ff0;">
        <h3>{{ number_format($active_deposits, 2) }}</h3>
        <p>أمانات يجب ردها</p>
    </div>
</div>

<div class="stats">
    <div class="stat-box" style="background: #0af;">
        <h3>{{ number_format($total_receivables, 2) }}</h3>
        <p>مستحق لنا (ديون + سلف)</p>
    </div>
    <div class="stat-box" style="background: {{ $net_liquidity >= 0 ? '#0f0' : '#f00' }}; {{ $net_liquidity < 0 ? 'color: #fff;' : '' }}">
        <h3>{{ number_format($net_liquidity, 2) }}</h3>
        <p>صافي السيولة المتاحة</p>
    </div>
    <div class="stat-box">
        <h3>{{ number_format($collected_insurance, 2) }}</h3>
        <p>تأمينات محصلة</p>
    </div>
</div>

<div class="card">
    <h2>تحليل السيولة</h2>
    
    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th>البند</th>
                <th>المبلغ</th>
                <th>الوصف</th>
            </tr>
        </thead>
        <tbody>
            <tr style="background: #e0ffe0;">
                <td><strong>الرصيد الفعلي</strong></td>
                <td><strong>{{ number_format($actual_cash, 2) }}</strong></td>
                <td>المبلغ الموجود فعلياً في الصناديق</td>
            </tr>
            <tr style="background: #ffe0e0;">
                <td>(-) الأدوار المستحقة</td>
                <td>{{ number_format($upcoming_obligations, 2) }}</td>
                <td>الجمعيات النشطة اللي لازم ندفعها</td>
            </tr>
            <tr style="background: #ffe0e0;">
                <td>(-) الأمانات</td>
                <td>{{ number_format($active_deposits, 2) }}</td>
                <td>أمانات العملاء اللي لازم نردها</td>
            </tr>
            <tr style="background: #e0e0ff;">
                <td>(+) المستحق لنا</td>
                <td>{{ number_format($total_receivables, 2) }}</td>
                <td>ديون وسلف لازم العملاء يسددوها</td>
            </tr>
            <tr style="background: {{ $net_liquidity >= 0 ? '#a0ffa0' : '#ffa0a0' }};">
                <td><strong>= صافي السيولة</strong></td>
                <td><strong>{{ number_format($net_liquidity, 2) }}</strong></td>
                <td>{{ $net_liquidity >= 0 ? 'وضع آمن ✓' : 'وضع خطر! ⚠' }}</td>
            </tr>
            <tr style="background: #ffffcc;">
                <td>احتياطي التأمينات</td>
                <td>{{ number_format($collected_insurance, 2) }}</td>
                <td>يمكن استخدامها في الطوارئ</td>
            </tr>
            <tr style="background: #ffffcc;">
                <td>غرامات معلقة</td>
                <td>{{ number_format($pending_penalties, 2) }}</td>
                <td>ممكن تتحصل قريباً</td>
            </tr>
        </tbody>
    </table>

    @if($net_liquidity < 0)
    <div style="background: #f00; color: #fff; padding: 15px; margin-top: 20px; border: 4px solid #000;">
        <h3>⚠ تحذير: عجز في السيولة!</h3>
        <p><strong>المطلوب:</strong> {{ number_format(abs($net_liquidity), 2) }}</p>
        <p><strong>الإجراءات المقترحة:</strong></p>
        <ul style="margin-right: 20px;">
            <li>تحصيل الديون المستحقة فوراً</li>
            <li>تأجيل بعض الأدوار بموافقة الأعضاء</li>
            <li>استخدام احتياطي التأمينات ({{ number_format($collected_insurance, 2) }})</li>
            <li>وقف منح سلف جديدة مؤقتاً</li>
        </ul>
    </div>
    @else
    <div style="background: #0f0; padding: 15px; margin-top: 20px; border: 4px solid #000;">
        <h3>✓ الوضع المالي جيد</h3>
        <p>السيولة المتاحة كافية لتغطية الالتزامات</p>
        <p><strong>فائض السيولة:</strong> {{ number_format($net_liquidity, 2) }}</p>
    </div>
    @endif
</div>

<div class="card">
    <h2>توصيات</h2>
    <ul style="margin-right: 20px; line-height: 2;">
        <li><strong>نسبة الأمان:</strong> يفضل أن يكون صافي السيولة على الأقل 20% من الالتزامات</li>
        <li><strong>الحد الآمن:</strong> {{ number_format($upcoming_obligations * 0.2, 2) }}</li>
        <li><strong>وضعك الحالي:</strong> 
            @php
                $safety_ratio = $upcoming_obligations > 0 ? ($net_liquidity / $upcoming_obligations) * 100 : 100;
            @endphp
            {{ number_format($safety_ratio, 1) }}%
            @if($safety_ratio >= 20)
                <span style="color: #0a0;">✓ ممتاز</span>
            @elseif($safety_ratio >= 10)
                <span style="color: #fa0;">⚠ مقبول</span>
            @else
                <span style="color: #f00;">✗ خطر</span>
            @endif
        </li>
    </ul>
</div>
@endsection

