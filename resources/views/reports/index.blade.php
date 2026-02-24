@extends('layout')

@section('title', 'التقارير')

@section('content')
<h1>التقارير</h1>

<div class="card">
    <h2>التقارير المتاحة</h2>
    
    <div style="margin: 20px 0;">
        <a href="{{ route('reports.payments-receipts') }}" class="btn" style="display: block; margin: 10px 0;">
            📊 تقرير المدفوعات والمقبوضات
        </a>
        
        <a href="{{ route('reports.client-activity') }}" class="btn" style="display: block; margin: 10px 0;">
            👤 تقرير حركة العميل
        </a>
        
        <a href="{{ route('reports.safe-activity') }}" class="btn" style="display: block; margin: 10px 0;">
            💰 تقرير حركة الصندوق
        </a>
        
        <a href="{{ route('reports.upcoming-completions') }}" class="btn" style="display: block; margin: 10px 0;">
            📅 العملاء المقبلين على إنهاء الجمعيات
        </a>
        
        <a href="{{ route('reports.liquidity') }}" class="btn" style="display: block; margin: 10px 0;">
            💵 تقرير السيولة والمخاطر المالية
        </a>
        
        <a href="{{ route('reports.risky-clients') }}" class="btn" style="display: block; margin: 10px 0;">
            ⚠ العملاء عالي المخاطر
        </a>
    </div>
</div>
@endsection

