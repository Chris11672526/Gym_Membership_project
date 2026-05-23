<x-app-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    <section class="relative overflow-hidden border-b border-white/5">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_100%_0%,rgba(37,99,235,.1),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-10">
            <p class="text-xs font-black uppercase tracking-[.2em] text-blue-400">Billing</p>
            <div class="mt-3 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <h1 class="text-4xl font-black">Payment Management</h1>
                <div class="flex gap-3">
                    <div class="rounded-xl border border-white/8 bg-neutral-900/70 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Revenue</p>
                        <p class="mt-1 text-lg font-black">PHP {{ number_format($totalRevenue, 0) }}</p>
                    </div>
                    <div class="rounded-xl border border-yellow-500/20 bg-yellow-950/20 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-yellow-600">Pending</p>
                        <p class="mt-1 text-lg font-black text-yellow-300">{{ $pendingPayments }}</p>
                    </div>
                    <div class="rounded-xl border border-white/8 bg-neutral-900/70 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Records</p>
                        <p class="mt-1 text-lg font-black">{{ $payments->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="pay-alert" class="hidden mx-auto max-w-7xl px-6 mt-4">
        <div id="pay-alert-inner" class="rounded-xl border px-5 py-3 text-sm font-bold"></div>
    </div>

    <main class="mx-auto max-w-7xl px-6 py-8">
        <div class="overflow-hidden rounded-xl border border-white/8 bg-neutral-900/70 backdrop-blur">
            <div class="overflow-x-auto">
                <table id="paymentsTable" class="w-full text-sm text-white" style="width:100%">
                    <thead>
                        <tr class="border-b border-white/8 text-[10px] font-black uppercase tracking-widest text-neutral-500">
                            <th class="px-5 py-3 text-left">Member</th>
                            <th class="px-5 py-3 text-left">Amount</th>
                            <th class="px-5 py-3 text-left">Method</th>
                            <th class="px-5 py-3 text-left">Reference</th>
                            <th class="px-5 py-3 text-left">Date</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr class="border-b border-white/5 last:border-0 hover:bg-white/2 transition" data-id="{{ $payment->id }}">
                                <td class="px-5 py-4 font-bold">{{ $payment->first_name }} {{ $payment->last_name }}</td>
                                <td class="px-5 py-4 font-black">PHP {{ number_format($payment->total_paid, 2) }}</td>
                                <td class="px-5 py-4 text-neutral-400">{{ $payment->payment_method }}</td>
                                <td class="px-5 py-4 font-mono text-xs text-neutral-500">{{ $payment->reference_no ?? '—' }}</td>
                                <td class="px-5 py-4 text-xs text-neutral-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</td>
                                <td class="px-5 py-4 status-cell">
                                    <span class="status-badge inline-block rounded-full border px-3 py-1 text-xs font-black
                                        {{ $payment->status === 'Paid' ? 'bg-green-950/60 text-green-300 border-green-500/30'
                                            : ($payment->status === 'Refunded' ? 'bg-blue-950/60 text-blue-300 border-blue-500/30'
                                            : 'bg-yellow-950/60 text-yellow-300 border-yellow-500/30') }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <select onchange="updatePaymentStatus({{ $payment->id }}, this)"
                                            class="rounded-lg border border-white/10 bg-neutral-950 px-3 py-1.5 text-xs text-white focus:border-blue-500/70 focus:outline-none">
                                        @foreach (['Paid', 'Pending', 'Refunded'] as $s)
                                            <option value="{{ $s }}" {{ $payment->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    #paymentsTable_wrapper .dataTables_filter input,
    #paymentsTable_wrapper .dataTables_length select {
        background: #09090b; border: 1px solid rgba(255,255,255,.1); color: #fff;
        border-radius: 8px; padding: 6px 12px; outline: none;
    }
    #paymentsTable_wrapper .dataTables_filter label,
    #paymentsTable_wrapper .dataTables_length label,
    #paymentsTable_wrapper .dataTables_info { color: #71717a; font-size: 13px; }
    #paymentsTable_wrapper .dataTables_paginate .paginate_button { color: #71717a !important; border-radius: 6px !important; padding: 4px 10px !important; }
    #paymentsTable_wrapper .dataTables_paginate .paginate_button.current { background: #2563eb !important; color: #fff !important; border: none !important; }
    #paymentsTable_wrapper .dataTables_paginate .paginate_button:hover { background: rgba(255,255,255,.08) !important; color: #fff !important; border: none !important; }
    table.dataTable thead th { border-bottom: 1px solid rgba(255,255,255,.08) !important; }
    table.dataTable tbody tr { background: transparent !important; }
    table.dataTable.no-footer { border-bottom: none !important; }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    const CSRF = '{{ csrf_token() }}';
    $(document).ready(() => $('#paymentsTable').DataTable({ pageLength: 15, order: [[4, 'desc']], columnDefs: [{ orderable: false, targets: 6 }] }));

    function updatePaymentStatus(id, select) {
        const status = select.value;
        fetch(`/admin/payments/${id}/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ status })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row   = select.closest('tr');
                const badge = row.querySelector('.status-badge');
                badge.textContent = status;
                badge.className = 'status-badge inline-block rounded-full border px-3 py-1 text-xs font-black '
                    + (status === 'Paid'     ? 'bg-green-950/60 text-green-300 border-green-500/30'
                    :  status === 'Refunded' ? 'bg-blue-950/60 text-blue-300 border-blue-500/30'
                    :                          'bg-yellow-950/60 text-yellow-300 border-yellow-500/30');
                showAlert('Payment status updated to ' + status + '.', 'success');
            } else {
                showAlert('Failed to update status.', 'error');
            }
        })
        .catch(() => showAlert('Server error.', 'error'));
    }

    function showAlert(msg, type) {
        const wrap  = document.getElementById('pay-alert');
        const inner = document.getElementById('pay-alert-inner');
        inner.textContent = msg;
        inner.className = type === 'success'
            ? 'rounded-xl border border-green-500/30 bg-green-950/40 px-5 py-3 text-sm font-bold text-green-300'
            : 'rounded-xl border border-red-500/30 bg-red-950/40 px-5 py-3 text-sm font-bold text-red-300';
        wrap.className = 'mx-auto max-w-7xl px-6 mt-4';
        setTimeout(() => wrap.className = 'hidden mx-auto max-w-7xl px-6 mt-4', 3000);
    }
</script>
</x-app-layout>
