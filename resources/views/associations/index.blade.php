@extends('layout')

@section('title', 'الجمعيات')

@section('content')
<h1>الجمعيات</h1>
<a href="{{ route('associations.create') }}" class="btn">+ إضافة جمعية</a>

<div class="card" style="margin-bottom: 20px;">
    <h2>🔍 فلاتر البحث</h2>
    <form method="GET" action="{{ route('associations.index') }}" id="filters-form">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
            <div>
                <label>البحث بالاسم</label>
                <input type="text" name="name" id="filter-name" value="{{ request('name') }}" placeholder="ابحث بالاسم..." style="width: 100%;">
            </div>
            <div>
                <label>الحالة</label>
                <select name="status" id="filter-status" style="width: 100%;">
                    <option value="">الكل</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            <div>
                <label>تاريخ البداية من</label>
                <input type="date" name="start_from" id="filter-start-from" value="{{ request('start_from') }}" style="width: 100%;">
            </div>
            <div>
                <label>تاريخ البداية إلى</label>
                <input type="date" name="start_to" id="filter-start-to" value="{{ request('start_to') }}" style="width: 100%;">
            </div>
            <div>
                <label>تاريخ النهاية من</label>
                <input type="date" name="end_from" id="filter-end-from" value="{{ request('end_from') }}" style="width: 100%;">
            </div>
            <div>
                <label>تاريخ النهاية إلى</label>
                <input type="date" name="end_to" id="filter-end-to" value="{{ request('end_to') }}" style="width: 100%;">
            </div>
        </div>
        <button type="submit" class="btn btn-success">تطبيق الفلاتر</button>
        <a href="{{ route('associations.index') }}" class="btn" style="background: #999; color: #fff;">مسح الفلاتر</a>
        <span style="margin-right: 15px; font-weight: bold; color: #4af;">
            عرض {{ $associations->firstItem() ?? 0 }} - {{ $associations->lastItem() ?? 0 }} من {{ $associations->total() }}
        </span>
    </form>
</div>

<div class="card">
    <table id="associations-table">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>المبلغ الشهري</th>
                <th>عدد الأعضاء</th>
                <th>الإجمالي</th>
                <th>تاريخ البداية</th>
                <th>تاريخ النهاية</th>
                <th>الحالة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody id="associations-tbody">
            @foreach($associations as $association)
            <tr data-name="{{ strtolower($association->name) }}" 
                data-status="{{ $association->status }}"
                data-start-date="{{ $association->start_date->format('Y-m-d') }}"
                data-end-date="{{ $association->end_date ? $association->end_date->format('Y-m-d') : '' }}"
                data-members-planned="{{ $association->members_count }}"
                data-members-actual="{{ $association->actual_members_count ?? 0 }}">
                <td>{{ $association->id }}</td>
                <td>
                    <a href="{{ route('associations.show', $association) }}">{{ $association->name }}</a>
                    @if($association->status === 'completed')
                        <span class="badge badge-completed" style="margin-right: 10px; background: #0f0; color: #000; font-weight: bold;">✓ مكتملة</span>
                    @endif
                </td>
                <td>{{ number_format($association->monthly_amount, 2) }}</td>
                <td>
                    <strong>{{ $association->members_count }} / {{ $association->actual_members_count ?? 0 }}</strong>
                    @if(($association->actual_members_count ?? 0) < $association->members_count)
                        <span style="background-color: #f00; color: #fff; padding: 2px 4px; border-radius: 4px;">(ناقص {{ $association->members_count - ($association->actual_members_count ?? 0) }})</span>
                    @endif
                </td>
                <td>{{ number_format($association->total_amount, 2) }}</td>
                <td>{{ $association->start_date->format('Y-m-d') }}</td>
                <td>
                    @if($association->end_date)
                        {{ $association->end_date->format('Y-m-d') }}
                    @else
                        <span style="color: #999;">-</span>
                    @endif
                </td>
                <td><span class="badge badge-{{ $association->status }}">{{ $association->status }}</span></td>
                <td>
                    <a href="{{ route('associations.show', $association) }}" class="btn">عرض</a>
                    <a href="{{ route('associations.edit', $association) }}" class="btn">تعديل</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($associations->hasPages())
    <div style="margin-top: 20px; display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
        {{ $associations->links() }}
    </div>
    @endif
</div>

<style>
.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 5px;
    flex-wrap: wrap;
}

.pagination li {
    display: inline-block;
}

.pagination li a,
.pagination li span {
    display: inline-block;
    padding: 8px 12px;
    text-decoration: none;
    border: 2px solid #000;
    font-weight: bold;
    transition: all 0.3s;
}

.pagination li a:hover {
    background: #000;
    color: #fff;
}

.pagination li.active span {
    background: #000;
    color: #fff;
}

.pagination li.disabled span {
    background: #ccc;
    color: #666;
    border-color: #ccc;
    cursor: not-allowed;
}
</style>
@endsection

