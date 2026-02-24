@extends('layout')

@section('title', 'جمعية: ' . $association->name)

@section('content')
<h1>{{ $association->name }}</h1>
<a href="{{ route('associations.edit', $association) }}" class="btn">تعديل</a>
<a href="{{ route('associations.edit-turns', $association) }}" class="btn btn-success">تعديل الأدوار</a>
<a href="{{ route('associations.index') }}" class="btn">رجوع</a>

<div class="card">
    <h2>معلومات الجمعية</h2>
    <p><strong>المبلغ الشهري:</strong> {{ number_format($association->monthly_amount, 2) }}</p>
    <p><strong>عدد الأعضاء:</strong> {{ $association->members_count }}</p>
    <p><strong>الإجمالي:</strong> {{ number_format($association->total_amount, 2) }}</p>
    <p><strong>التأمين الكلي:</strong> {{ number_format($association->insurance_amount, 2) }}</p>
    <p><strong>العمولة:</strong> {{ number_format($association->commission_amount, 2) }} ({{ $association->commission_type == 'percentage' ? 'نسبة' : 'ثابت' }})</p>
    <p><strong>تاريخ البداية:</strong> {{ $association->start_date->format('Y-m-d') }}</p>
    <p><strong>الحالة:</strong> <span class="badge badge-{{ $association->status }}">{{ $association->status }}</span></p>
</div>

<div class="card">
    <h2>الأعضاء والأدوار</h2>
    <table>
        <thead>
            <tr>
                <th>رقم الدور</th>
                <th>العميل</th>
                <th>تاريخ الاستحقاق</th>
                <th>التأمين</th>
                <th>حالة التأمين</th>
                <th>حالة القبض</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($association->members->sortBy('turn_number') as $member)
            <tr>
                <td>{{ $member->turn_number }}</td>
                <td><a href="{{ route('clients.show', $member->client) }}">{{ $member->client->name }}</a></td>
                <td>{{ $member->due_date->format('Y-m-d') }}</td>
                <td>{{ number_format($member->insurance_amount, 2) }}</td>
                <td><span class="badge badge-{{ $member->insurance_status }}">{{ $member->insurance_status }}</span></td>
                <td><span class="badge badge-{{ $member->collection_status }}">{{ $member->collection_status }}</span></td>
                <td>
                    @if($member->collection_status == 'pending')
                        <button class="btn btn-success" onclick="openCollectModal({{ $member->id }}, {{ $member->client_id }}, '{{ $member->client->name }}', {{ $association->total_amount }})">قبض</button>
                        <form action="{{ route('members.forceEnd', $member) }}" method="POST" style="display:inline;">
                            @csrf
                            <button class="btn btn-danger" onclick="return confirm('متأكد من الإنهاء القصري؟')">إنهاء قصري</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal للقبض -->
<div id="collectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: #fff; padding: 30px; border: 4px solid #000; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h2 style="margin-bottom: 20px;">تسجيل القبض</h2>
        <form id="collectForm" method="POST">
            @csrf
            <input type="hidden" name="member_id" id="modal_member_id">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">العميل</label>
                <input type="text" id="modal_client_name" readonly style="width: 100%; padding: 10px; border: 3px solid #000; background: #f5f5f5;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">الصندوق *</label>
                <select name="safe_id" id="modal_safe_id" required style="width: 100%; padding: 10px; border: 3px solid #000;">
                    <option value="">اختر صندوق</option>
                    @foreach($safes as $safe)
                        <option value="{{ $safe->id }}">{{ $safe->name }} ({{ number_format($safe->balance, 2) }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">المبلغ *</label>
                <input type="number" step="0.01" name="amount" id="modal_amount" required style="width: 100%; padding: 10px; border: 3px solid #000;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">تاريخ الدفع *</label>
                <input type="date" name="payment_date" id="modal_payment_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 10px; border: 3px solid #000;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">ملاحظات</label>
                <textarea name="notes" id="modal_notes" rows="3" style="width: 100%; padding: 10px; border: 3px solid #000;"></textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" class="btn btn-success" style="flex: 1;">حفظ</button>
                <button type="button" onclick="closeCollectModal()" class="btn" style="flex: 1; background: #999; color: #fff;">إلغاء</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentMemberId = null;

function openCollectModal(memberId, clientId, clientName, defaultAmount) {
    currentMemberId = memberId;
    document.getElementById('modal_member_id').value = memberId;
    document.getElementById('modal_client_name').value = clientName;
    document.getElementById('modal_amount').value = defaultAmount || '';
    document.getElementById('modal_payment_date').value = '{{ date('Y-m-d') }}';
    document.getElementById('modal_notes').value = '';
    document.getElementById('modal_safe_id').value = '';
    document.getElementById('collectModal').style.display = 'flex';
}

function closeCollectModal() {
    document.getElementById('collectModal').style.display = 'none';
    currentMemberId = null;
}

// إغلاق Modal عند الضغط خارجها
document.getElementById('collectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCollectModal();
    }
});

// معالجة form submission
document.getElementById('collectForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const memberId = formData.get('member_id');
    
    try {
        const response = await fetch('/association-members/' + memberId + '/collect', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            closeCollectModal();
            location.reload(); // إعادة تحميل الصفحة لتحديث الحالة
        } else {
            alert('خطأ: ' + (data.message || 'حدث خطأ'));
        }
    } catch (error) {
        alert('خطأ في الاتصال');
        console.error(error);
    }
});
</script>
@endsection

