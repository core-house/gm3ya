@extends('layout')

@section('title', 'إضافة عميل')

@section('content')
<h1>إضافة عميل جديد</h1>

<div class="card">
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        
        <label>الاسم *</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>الهاتف</label>
        <input type="text" name="phone" value="{{ old('phone') }}">

        <label>العنوان</label>
        <textarea name="address" rows="3">{{ old('address') }}</textarea>

        <label>التقييم</label>
        <input type="number" name="rate" value="{{ old('rate', 0) }}">

        <label>الرقم القومي</label>
        <input type="text" name="national_id" value="{{ old('national_id') }}">

        <label>جهة العمل</label>
        <input type="text" name="work_place" value="{{ old('work_place') }}">

        <label>المرتب</label>
        <input type="number" step="0.01" name="salary" value="{{ old('salary') }}">

        <h3 style="margin-top: 20px;">معلومات الكفيل/الضامن</h3>

        <label>اسم الكفيل</label>
        <input type="text" name="guarantor_name" value="{{ old('guarantor_name') }}">

        <label>هاتف الكفيل</label>
        <input type="text" name="guarantor_phone" value="{{ old('guarantor_phone') }}">

        <label>الرقم القومي للكفيل</label>
        <input type="text" name="guarantor_national_id" value="{{ old('guarantor_national_id') }}">

        <label>عنوان الكفيل</label>
        <textarea name="guarantor_address" rows="2">{{ old('guarantor_address') }}</textarea>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('clients.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

