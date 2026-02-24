@extends('layout')

@section('title', 'إضافة جمعية')

@section('content')
<h1>إضافة جمعية جديدة</h1>

<div class="card">
    <form action="{{ route('associations.store') }}" method="POST">
        @csrf
        
        <label>اسم الجمعية *</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>المبلغ الشهري *</label>
        <input type="number" step="0.01" name="monthly_amount" value="{{ old('monthly_amount') }}" required>

        <label>عدد الأفراد *</label>
        <input type="number" name="members_count" value="{{ old('members_count') }}" required>

        <label>تاريخ البداية *</label>
        <input type="date" name="start_date" value="{{ old('start_date') }}" required>

        <label>التأمين (إجمالي)</label>
        <input type="number" step="0.01" name="insurance_amount" value="{{ old('insurance_amount', 0) }}">

        <label>العمولة</label>
        <input type="number" step="0.01" name="commission_amount" value="{{ old('commission_amount', 0) }}">

        <label>نوع العمولة</label>
        <select name="commission_type">
            <option value="fixed">ثابت</option>
            <option value="percentage">نسبة %</option>
        </select>

        <label>اختر العملاء (بالترتيب) - اختياري</label>
        <p style="color: #666; font-size: 14px; margin-bottom: 10px;">يمكنك إضافة الأعضاء لاحقاً من صفحة الجمعية</p>
        <div id="clients-container">
            @foreach($clients as $client)
            <div>
                <input type="checkbox" name="client_ids[]" value="{{ $client->id }}" id="client_{{ $client->id }}">
                <label for="client_{{ $client->id }}" style="display: inline;">{{ $client->name }}</label>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('associations.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

