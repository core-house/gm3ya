@extends('layout')

@section('title', 'تعديل صندوق')

@section('content')
<h1>تعديل صندوق: {{ $safe->name }}</h1>

<div class="card">
    <form action="{{ route('safes.update', $safe) }}" method="POST">
        @csrf @method('PUT')
        
        <label>اسم الصندوق *</label>
        <input type="text" name="name" value="{{ old('name', $safe->name) }}" required>

        <label>الوصف</label>
        <textarea name="description" rows="3">{{ old('description', $safe->description) }}</textarea>

        <p><strong>الرصيد الحالي:</strong> {{ number_format($safe->balance, 2) }}</p>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('safes.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

