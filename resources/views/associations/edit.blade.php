@extends('layout')

@section('title', 'تعديل جمعية')

@section('content')
<h1>تعديل جمعية: {{ $association->name }}</h1>

<div class="card">
    <form action="{{ route('associations.update', $association) }}" method="POST">
        @csrf @method('PUT')
        
        <label>اسم الجمعية *</label>
        <input type="text" name="name" value="{{ old('name', $association->name) }}" required>

        <label>الحالة</label>
        <select name="status">
            <option value="active" {{ $association->status == 'active' ? 'selected' : '' }}>نشط</option>
            <option value="completed" {{ $association->status == 'completed' ? 'selected' : '' }}>مكتمل</option>
            <option value="cancelled" {{ $association->status == 'cancelled' ? 'selected' : '' }}>ملغي</option>
        </select>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('associations.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

