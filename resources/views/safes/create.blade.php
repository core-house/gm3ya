@extends('layout')

@section('title', 'إضافة صندوق')

@section('content')
<h1>إضافة صندوق جديد</h1>

<div class="card">
    <form action="{{ route('safes.store') }}" method="POST">
        @csrf
        
        <label>اسم الصندوق *</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>الرصيد الافتتاحي</label>
        <input type="number" step="0.01" name="balance" value="{{ old('balance', 0) }}">

        <label>الوصف</label>
        <textarea name="description" rows="3">{{ old('description') }}</textarea>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('safes.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

