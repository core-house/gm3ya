@extends('layout')

@section('title', 'تعديل أمانة')

@section('content')
<h1>تعديل أمانة #{{ $deposit->id }}</h1>

<div class="card">
    <form action="{{ route('deposits.update', $deposit) }}" method="POST">
        @csrf @method('PUT')
        
        <p><strong>العميل:</strong> {{ $deposit->client->name }}</p>
        <p><strong>المبلغ:</strong> {{ number_format($deposit->amount, 2) }}</p>
        <p><strong>التاريخ:</strong> {{ $deposit->deposit_date->format('Y-m-d') }}</p>

        <label>الحالة</label>
        <select name="status">
            <option value="active" {{ $deposit->status == 'active' ? 'selected' : '' }}>نشط</option>
            <option value="returned" {{ $deposit->status == 'returned' ? 'selected' : '' }}>مُرجعة</option>
        </select>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('deposits.index') }}" class="btn">إلغاء</a>
    </form>
</div>
@endsection

