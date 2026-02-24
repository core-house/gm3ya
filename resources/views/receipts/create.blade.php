@extends('layout')

@section('title', 'تسجيل قبض')

@section('content')
<h1>تسجيل قبض جديد</h1>

<div class="card">
    <form action="{{ route('receipts.store') }}" method="POST">
        @csrf
        
        <label>العميل *</label>
        <div style="position: relative;">
            <input type="text" id="client_search" placeholder="ابحث عن عميل..." autocomplete="off" required style="width: 100%; padding: 10px; border: 3px solid #000;">
            <div id="client_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 3px solid #000; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; margin-top: -3px;"></div>
        </div>
        <input type="hidden" name="client_id" id="client_id" required>

        <label>الصندوق *</label>
        <select name="safe_id" required>
            <option value="">اختر صندوق</option>
            @foreach($safes as $safe)
            <option value="{{ $safe->id }}">{{ $safe->name }} ({{ number_format($safe->balance, 2) }})</option>
            @endforeach
        </select>

        <label>المبلغ *</label>
        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" required>

        <label>النوع *</label>
        <select name="type" required>
            <option value="association">جمعية</option>
            <option value="loan">سداد سلفة</option>
            <option value="deposit">أمانة</option>
        </select>

        <label>تاريخ القبض *</label>
        <input type="date" name="receipt_date" value="{{ old('receipt_date', date('Y-m-d')) }}" required>

        <label>ملاحظات</label>
        <textarea name="notes" rows="3">{{ old('notes') }}</textarea>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('receipts.index') }}" class="btn">إلغاء</a>
    </form>
</div>

<script>
// بيانات العملاء
const clients = @json($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->name]));

const clientSearch = document.getElementById('client_search');
const clientIdInput = document.getElementById('client_id');
const clientDropdown = document.getElementById('client_dropdown');

// عرض نتائج البحث
function showClientResults(searchValue) {
    if (!searchValue || searchValue.length < 1) {
        clientDropdown.style.display = 'none';
        return;
    }
    
    const filtered = clients.filter(c => 
        c.name.toLowerCase().includes(searchValue.toLowerCase())
    );
    
    if (filtered.length === 0) {
        clientDropdown.innerHTML = '<div style="padding: 10px; text-align: center; color: #999;">لا توجد نتائج</div>';
        clientDropdown.style.display = 'block';
        return;
    }
    
    clientDropdown.innerHTML = filtered.map(client => {
        const nameEscaped = client.name.replace(/'/g, "&#39;").replace(/"/g, "&quot;");
        return `<div class="client-option" data-id="${client.id}" data-name="${nameEscaped}" style="padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;" onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'" onclick="selectClient(${client.id}, ${JSON.stringify(client.name)})">${client.name}</div>`;
    }).join('');
    
    clientDropdown.style.display = 'block';
}

// اختيار عميل
function selectClient(id, name) {
    clientIdInput.value = id;
    clientSearch.value = name;
    clientDropdown.style.display = 'none';
}

// عند الكتابة في البحث
clientSearch.addEventListener('input', function() {
    showClientResults(this.value);
});

// إغلاق القائمة عند الضغط خارجها
document.addEventListener('click', function(e) {
    if (!clientSearch.contains(e.target) && !clientDropdown.contains(e.target)) {
        clientDropdown.style.display = 'none';
    }
});

// التحقق قبل الإرسال
document.querySelector('form').addEventListener('submit', function(e) {
    if (!clientIdInput.value) {
        e.preventDefault();
        alert('يرجى اختيار عميل صحيح من القائمة');
        clientSearch.focus();
        return false;
    }
});
</script>
@endsection

