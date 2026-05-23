<x-app-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    {{-- Header --}}
    <section class="relative overflow-hidden border-b border-white/5">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_0%_0%,rgba(37,99,235,.12),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-10">
            <p class="text-xs font-black uppercase tracking-[.2em] text-blue-400">Roster</p>
            <div class="mt-3 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h1 class="text-4xl font-black">Member Records</h1>
                    <p class="mt-2 text-neutral-400">Manage all gym members — add, edit, or remove records.</p>
                </div>
                <div class="flex gap-3">
                    <div class="rounded-xl border border-white/8 bg-neutral-900/70 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Total</p>
                        <p class="mt-1 text-xl font-black">{{ $totalCustomers }}</p>
                    </div>
                    <div class="rounded-xl border border-green-500/20 bg-green-950/20 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-green-600">Active</p>
                        <p class="mt-1 text-xl font-black text-green-300">{{ $activeMemberships }}</p>
                    </div>
                    <button onclick="openAddModal()"
                            class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-blue-900/30 transition hover:bg-blue-500">
                        + Add Member
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Alert --}}
    <div id="crud-alert" class="hidden mx-auto max-w-7xl px-6 mt-4">
        <div id="crud-alert-inner" class="rounded-xl border px-5 py-3 text-sm font-bold"></div>
    </div>

    <main class="mx-auto max-w-7xl px-6 py-8">

        {{-- DataTable --}}
        <div class="overflow-hidden rounded-xl border border-white/8 bg-neutral-900/70 backdrop-blur">
            <div class="overflow-x-auto">
                <table id="membersTable" class="w-full text-sm text-white" style="width:100%">
                    <thead>
                        <tr class="border-b border-white/8 text-[10px] font-black uppercase tracking-widest text-neutral-500">
                            <th class="px-5 py-3 text-left">Member</th>
                            <th class="px-5 py-3 text-left">Phone</th>
                            <th class="px-5 py-3 text-left">Branch</th>
                            <th class="px-5 py-3 text-left">Plan</th>
                            <th class="px-5 py-3 text-left">Expires</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            @php
                                $status = $customer->membership_status ?? $customer->status;
                                $statusClass = $status === 'Active'
                                    ? 'bg-green-950/60 text-green-300 border-green-500/30'
                                    : ($status === 'Expired' ? 'bg-red-950/60 text-red-300 border-red-500/30'
                                    : 'bg-neutral-800 text-neutral-400 border-white/10');
                            @endphp
                            <tr class="border-b border-white/5 transition hover:bg-white/2 last:border-0"
                                data-id="{{ $customer->id }}"
                                data-first="{{ $customer->first_name }}"
                                data-last="{{ $customer->last_name }}"
                                data-email="{{ $customer->email }}"
                                data-phone="{{ $customer->phone }}"
                                data-gender="{{ $customer->gender }}"
                                data-birthdate="{{ $customer->birthdate }}"
                                data-branch="{{ $customer->branch_id }}"
                                data-status="{{ $customer->status }}">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-blue-600/15 text-xs font-black text-blue-300">
                                            {{ strtoupper(substr($customer->first_name,0,1).substr($customer->last_name,0,1)) }}
                                        </div>
                                        <div>
                                            <p class="font-black">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                            <p class="text-xs text-neutral-500">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-neutral-400">{{ $customer->phone }}</td>
                                <td class="px-5 py-4 text-neutral-400">{{ $customer->branch_name ?? '—' }}</td>
                                <td class="px-5 py-4 font-bold">{{ $customer->plan_name ?? '—' }}</td>
                                <td class="px-5 py-4 text-xs text-neutral-500">{{ $customer->expiration_date ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-block rounded-full border px-3 py-1 text-xs font-black {{ $statusClass }}">{{ $status }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button onclick="openEditModal(this.closest('tr'))"
                                            class="mr-2 rounded-lg border border-blue-500/30 px-3 py-1.5 text-xs font-black text-blue-400 transition hover:bg-blue-600/10">
                                        Edit
                                    </button>
                                    <button onclick="deleteMember({{ $customer->id }}, this.closest('tr'))"
                                            class="rounded-lg border border-red-500/30 px-3 py-1.5 text-xs font-black text-red-400 transition hover:bg-red-600/10">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

{{-- ── Add Member Modal ─────────────────────────────────────────────────── --}}
<div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-neutral-900 p-8 shadow-2xl mx-4">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-black">Add New Member</h2>
            <button onclick="closeModal('addModal')" class="text-neutral-500 hover:text-white">✕</button>
        </div>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">First Name</label>
                    <input type="text" id="add-first" placeholder="Juan" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Last Name</label>
                    <input type="text" id="add-last" placeholder="Dela Cruz" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Email</label>
                <input type="email" id="add-email" placeholder="juan@email.com" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Phone</label>
                    <input type="text" id="add-phone" placeholder="09XXXXXXXXX" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Gender</label>
                    <select id="add-gender" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                        <option>Male</option><option>Female</option><option>Other</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Birthdate</label>
                    <input type="date" id="add-birthdate" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Branch</label>
                    <select id="add-branch" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                        @foreach(DB::table('branches')->whereNull('deleted_at')->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Membership Plan</label>
                    <select id="add-plan" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                        <option value="">— None —</option>
                        @foreach(DB::table('membership_plans')->where('is_active',1)->get() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Account Status</label>
                    <select id="add-status" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                        <option>Active</option><option>Inactive</option><option>Suspended</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="add-alert" class="hidden mt-4 rounded-xl border px-4 py-3 text-sm font-semibold"></div>
        <button onclick="storeMember()" id="add-btn"
                class="mt-6 w-full rounded-xl bg-blue-600 py-3 text-sm font-black uppercase tracking-widest text-white transition hover:bg-blue-500 active:scale-95">
            Save Member
        </button>
    </div>
</div>

{{-- ── Edit Member Modal ────────────────────────────────────────────────── --}}
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-neutral-900 p-8 shadow-2xl mx-4">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-black">Edit Member</h2>
            <button onclick="closeModal('editModal')" class="text-neutral-500 hover:text-white">✕</button>
        </div>
        <input type="hidden" id="edit-id">
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">First Name</label>
                    <input type="text" id="edit-first" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Last Name</label>
                    <input type="text" id="edit-last" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Email</label>
                <input type="email" id="edit-email" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Phone</label>
                    <input type="text" id="edit-phone" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Gender</label>
                    <select id="edit-gender" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                        <option>Male</option><option>Female</option><option>Other</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Birthdate</label>
                    <input type="date" id="edit-birthdate" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                </div>
                <div>
                    <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Branch</label>
                    <select id="edit-branch" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                        @foreach(DB::table('branches')->whereNull('deleted_at')->get() as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Account Status</label>
                <select id="edit-status" class="w-full rounded-xl border border-white/10 bg-neutral-950 px-4 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                    <option>Active</option><option>Inactive</option><option>Suspended</option>
                </select>
            </div>
        </div>
        <div id="edit-alert" class="hidden mt-4 rounded-xl border px-4 py-3 text-sm font-semibold"></div>
        <button onclick="updateMember()" id="edit-btn"
                class="mt-6 w-full rounded-xl bg-blue-600 py-3 text-sm font-black uppercase tracking-widest text-white transition hover:bg-blue-500 active:scale-95">
            Update Member
        </button>
    </div>
</div>

{{-- jQuery + DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    #membersTable_wrapper .dataTables_filter input,
    #membersTable_wrapper .dataTables_length select {
        background: #09090b; border: 1px solid rgba(255,255,255,.1); color: #fff;
        border-radius: 8px; padding: 6px 12px; outline: none;
    }
    #membersTable_wrapper .dataTables_filter label,
    #membersTable_wrapper .dataTables_length label,
    #membersTable_wrapper .dataTables_info { color: #71717a; font-size: 13px; }
    #membersTable_wrapper .dataTables_paginate .paginate_button {
        color: #71717a !important; border-radius: 6px !important; padding: 4px 10px !important;
    }
    #membersTable_wrapper .dataTables_paginate .paginate_button.current {
        background: #2563eb !important; color: #fff !important; border: none !important;
    }
    #membersTable_wrapper .dataTables_paginate .paginate_button:hover {
        background: rgba(255,255,255,.08) !important; color: #fff !important; border: none !important;
    }
    table.dataTable thead th { border-bottom: 1px solid rgba(255,255,255,.08) !important; }
    table.dataTable tbody tr { background: transparent !important; }
    table.dataTable.no-footer { border-bottom: none !important; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    const CSRF = '{{ csrf_token() }}';

    $(document).ready(function () {
        $('#membersTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
            columnDefs: [{ orderable: false, targets: 6 }],
        });
    });

    function openAddModal() {
        document.getElementById('addModal').style.display = 'flex';
        document.getElementById('add-alert').className = 'hidden mt-4 rounded-xl border px-4 py-3 text-sm font-semibold';
    }

    function openEditModal(row) {
        const d = row.dataset;
        document.getElementById('edit-id').value        = d.id;
        document.getElementById('edit-first').value     = d.first;
        document.getElementById('edit-last').value      = d.last;
        document.getElementById('edit-email').value     = d.email;
        document.getElementById('edit-phone').value     = d.phone;
        document.getElementById('edit-gender').value    = d.gender;
        document.getElementById('edit-birthdate').value = d.birthdate;
        document.getElementById('edit-branch').value    = d.branch;
        document.getElementById('edit-status').value    = d.status;
        document.getElementById('editModal').style.display = 'flex';
        document.getElementById('edit-alert').className = 'hidden mt-4 rounded-xl border px-4 py-3 text-sm font-semibold';
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function modalAlert(prefix, msg, type) {
        const el = document.getElementById(prefix + '-alert');
        el.textContent = msg;
        el.className = type === 'success'
            ? 'mt-4 rounded-xl border border-green-500/30 bg-green-950/40 px-4 py-3 text-sm font-semibold text-green-300'
            : 'mt-4 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm font-semibold text-red-300';
    }

    function showPageAlert(msg, type) {
        const wrap  = document.getElementById('crud-alert');
        const inner = document.getElementById('crud-alert-inner');
        inner.textContent = msg;
        inner.className = type === 'success'
            ? 'rounded-xl border border-green-500/30 bg-green-950/40 px-5 py-3 text-sm font-bold text-green-300'
            : 'rounded-xl border border-red-500/30 bg-red-950/40 px-5 py-3 text-sm font-bold text-red-300';
        wrap.className = 'mx-auto max-w-7xl px-6 mt-4';
        setTimeout(() => wrap.className = 'hidden mx-auto max-w-7xl px-6 mt-4', 3000);
    }

    function storeMember() {
        const btn = document.getElementById('add-btn');
        btn.disabled = true; btn.textContent = 'Saving…';

        fetch('{{ route("admin.members.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({
                first_name: document.getElementById('add-first').value,
                last_name:  document.getElementById('add-last').value,
                email:      document.getElementById('add-email').value,
                phone:      document.getElementById('add-phone').value,
                gender:     document.getElementById('add-gender').value,
                birthdate:  document.getElementById('add-birthdate').value,
                branch_id:  document.getElementById('add-branch').value,
                membership_plan_id: document.getElementById('add-plan').value,
                status:     document.getElementById('add-status').value,
            })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false; btn.textContent = 'Save Member';
            if (data.success) {
                closeModal('addModal');
                showPageAlert('Member added successfully.', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Validation error.');
                modalAlert('add', errors, 'error');
            }
        })
        .catch(() => { btn.disabled = false; btn.textContent = 'Save Member'; modalAlert('add', 'Server error.', 'error'); });
    }

    function updateMember() {
        const btn = document.getElementById('edit-btn');
        const id  = document.getElementById('edit-id').value;
        btn.disabled = true; btn.textContent = 'Updating…';

        fetch(`/admin/members/${id}/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({
                first_name: document.getElementById('edit-first').value,
                last_name:  document.getElementById('edit-last').value,
                email:      document.getElementById('edit-email').value,
                phone:      document.getElementById('edit-phone').value,
                gender:     document.getElementById('edit-gender').value,
                birthdate:  document.getElementById('edit-birthdate').value,
                branch_id:  document.getElementById('edit-branch').value,
                status:     document.getElementById('edit-status').value,
            })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false; btn.textContent = 'Update Member';
            if (data.success) {
                closeModal('editModal');
                showPageAlert('Member updated successfully.', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                const errors = data.errors ? Object.values(data.errors).flat().join(' ') : (data.message || 'Validation error.');
                modalAlert('edit', errors, 'error');
            }
        })
        .catch(() => { btn.disabled = false; btn.textContent = 'Update Member'; modalAlert('edit', 'Server error.', 'error'); });
    }

    function deleteMember(id, row) {
        if (!confirm('Delete this member? This cannot be undone.')) return;

        fetch(`/admin/members/${id}/delete`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                $('#membersTable').DataTable().row($(row)).remove().draw();
                showPageAlert('Member deleted.', 'success');
            } else {
                showPageAlert(data.message || 'Could not delete.', 'error');
            }
        })
        .catch(() => showPageAlert('Server error.', 'error'));
    }

    // Close modal on backdrop click
    ['addModal','editModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) closeModal(id);
        });
    });
</script>
</x-app-layout>
