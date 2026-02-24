@extends('layout')

@section('title', 'تعديل الأدوار - ' . $association->name)

@section('content')
<h1>تعديل الأدوار - {{ $association->name }}</h1>
<a href="{{ route('associations.show', $association) }}" class="btn">رجوع</a>

<div class="card">
    <h2>الأعضاء الحاليون</h2>
    <form action="{{ route('associations.update-turns', $association) }}" method="POST" id="update-turns-form">
        @csrf
        <table>
            <thead>
                <tr>
                    <th>رقم الدور</th>
                    <th>العميل</th>
                    <th>تاريخ الاستحقاق</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody id="members-tbody">
                @foreach($association->members->sortBy('turn_number') as $index => $member)
                <tr>
                    <td>
                        <input type="hidden" name="members[{{ $index }}][id]" value="{{ $member->id }}">
                        <input type="number" name="members[{{ $index }}][turn_number]" value="{{ $member->turn_number }}" min="1" required style="width: 80px;">
                    </td>
                    <td>
                        <select name="members[{{ $index }}][client_id]" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $member->client_id == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="date" name="members[{{ $index }}][due_date]" value="{{ $member->due_date->format('Y-m-d') }}" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="deleteMember('{{ $member->id }}')">حذف</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success" style="margin-top: 20px;">حفظ التعديلات</button>
    </form>
</div>

<script>
function deleteMember(memberId) {
    if (!confirm('هل أنت متأكد من حذف هذا العضو؟')) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("/association-members") }}/' + memberId;
    
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<div class="card">
    <h2>إضافة عضو جديد</h2>
    <form id="add-member-form">
        @csrf
        <label>العميل *</label>
        <select name="client_id" id="new_client_id" required>
            <option value="">اختر العميل</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
            @endforeach
        </select>

        <label>رقم الدور *</label>
        <input type="number" name="turn_number" id="new_turn_number" min="1" required>

        <label>تاريخ الاستحقاق *</label>
        <input type="date" name="due_date" id="new_due_date" required>

        <button type="submit" class="btn btn-success" id="add-member-btn">إضافة عضو</button>
        <span id="add-member-status" style="margin-right: 10px;"></span>
    </form>
</div>

<script>
const clientsData = @json($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->name]));

document.getElementById('add-member-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = document.getElementById('add-member-btn');
    const status = document.getElementById('add-member-status');
    const form = this;
    
    // تعطيل الزر
    btn.disabled = true;
    btn.textContent = 'جاري الإضافة...';
    status.textContent = '';
    
    const formData = new FormData(form);
    formData.append('_token', '{{ csrf_token() }}');
    
    try {
        const response = await fetch('{{ route("associations.add-member", $association) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // إضافة الصف الجديد إلى الجدول
            const tbody = document.getElementById('members-tbody');
            const rowCount = tbody.children.length;
            const newRow = document.createElement('tr');
            
            const turnNumber = document.getElementById('new_turn_number').value;
            const dueDate = document.getElementById('new_due_date').value;
            
            // بناء select للعملاء
            let clientsOptions = '';
            clientsData.forEach(client => {
                const selected = client.id == data.member.client_id ? 'selected' : '';
                clientsOptions += `<option value="${client.id}" ${selected}>${client.name}</option>`;
            });
            
            newRow.innerHTML = `
                <td>
                    <input type="hidden" name="members[${rowCount}][id]" value="${data.member.id}">
                    <input type="number" name="members[${rowCount}][turn_number]" value="${turnNumber}" min="1" required style="width: 80px;">
                </td>
                <td>
                    <select name="members[${rowCount}][client_id]" required>
                        ${clientsOptions}
                    </select>
                </td>
                <td>
                    <input type="date" name="members[${rowCount}][due_date]" value="${dueDate}" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="deleteMember('${data.member.id}')">حذف</button>
                </td>
            `;
            
            tbody.appendChild(newRow);
            
            // إعادة تعيين الـ form
            form.reset();
            
            status.textContent = '✓ تم الإضافة بنجاح';
            status.style.color = '#0f0';
            
            setTimeout(() => {
                status.textContent = '';
            }, 3000);
        } else {
            const errorMsg = data.message || data.errors ? Object.values(data.errors).flat().join(', ') : 'حدث خطأ';
            status.textContent = '✗ خطأ: ' + errorMsg;
            status.style.color = '#f00';
        }
    } catch (error) {
        status.textContent = '✗ خطأ في الاتصال';
        status.style.color = '#f00';
        console.error(error);
    } finally {
        btn.disabled = false;
        btn.textContent = 'إضافة عضو';
    }
});

function deleteMember(memberId) {
    if (!confirm('هل أنت متأكد من حذف هذا العضو؟')) {
        return;
    }
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ url("/association-members") }}/' + memberId;
    
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection

