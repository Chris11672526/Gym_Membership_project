<x-app-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    <section class="relative overflow-hidden border-b border-white/5">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_50%_40%_at_50%_0%,rgba(37,99,235,.1),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-10">
            <p class="text-xs font-black uppercase tracking-[.2em] text-blue-400">Attendance</p>
            <div class="mt-3 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h1 class="text-4xl font-black">Sessions & Attendance</h1>
                    <p class="mt-2 text-neutral-400">{{ count($attendance) }} session records total.</p>
                </div>
                <div class="rounded-xl border border-white/8 bg-neutral-900/70 px-5 py-3 text-center">
                    <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Total Sessions</p>
                    <p class="mt-1 text-xl font-black">{{ $sessionCount }}</p>
                </div>
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-6 py-8">
        <div class="overflow-hidden rounded-xl border border-white/8 bg-neutral-900/70 backdrop-blur">
            <div class="overflow-x-auto">
                <table id="sessionsTable" class="w-full text-sm text-white" style="width:100%">
                    <thead>
                        <tr class="border-b border-white/8 text-[10px] font-black uppercase tracking-widest text-neutral-500">
                            <th class="px-5 py-3 text-left">Member</th>
                            <th class="px-5 py-3 text-left">Branch</th>
                            <th class="px-5 py-3 text-left">Class</th>
                            <th class="px-5 py-3 text-left">Check In</th>
                            <th class="px-5 py-3 text-left">Check Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendance as $session)
                            <tr class="border-b border-white/5 last:border-0 hover:bg-white/2 transition">
                                <td class="px-5 py-4 font-black">
                                    {{ $session->first_name }} {{ $session->last_name }}
                                </td>
                                <td class="px-5 py-4 text-neutral-400">{{ $session->branch_name }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-block rounded-full border border-blue-500/30 bg-blue-950/40 px-3 py-1 text-xs font-black text-blue-300">
                                        {{ $session->class_name ?? 'Open Gym' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-xs text-neutral-400">
                                    {{ \Carbon\Carbon::parse($session->check_in)->format('M d, Y h:i A') }}
                                </td>
                                <td class="px-5 py-4 text-xs">
                                    @if ($session->check_out)
                                        <span class="text-neutral-400">{{ \Carbon\Carbon::parse($session->check_out)->format('h:i A') }}</span>
                                    @else
                                        <span class="font-bold text-green-400">● Active</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-neutral-500">
                                    No attendance records yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<style>
    #sessionsTable_wrapper .dataTables_filter input,
    #sessionsTable_wrapper .dataTables_length select {
        background: #09090b; border: 1px solid rgba(255,255,255,.1);
        color: #fff; border-radius: 8px; padding: 6px 12px; outline: none;
    }
    #sessionsTable_wrapper .dataTables_filter label,
    #sessionsTable_wrapper .dataTables_length label,
    #sessionsTable_wrapper .dataTables_info { color: #71717a; font-size: 13px; }
    #sessionsTable_wrapper .dataTables_paginate .paginate_button { color: #71717a !important; border-radius: 6px !important; padding: 4px 10px !important; }
    #sessionsTable_wrapper .dataTables_paginate .paginate_button.current { background: #2563eb !important; color: #fff !important; border: none !important; }
    #sessionsTable_wrapper .dataTables_paginate .paginate_button:hover { background: rgba(255,255,255,.08) !important; color: #fff !important; border: none !important; }
    table.dataTable thead th { border-bottom: 1px solid rgba(255,255,255,.08) !important; }
    table.dataTable tbody tr { background: transparent !important; }
    table.dataTable.no-footer { border-bottom: none !important; }
</style>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(() => $('#sessionsTable').DataTable({
    pageLength: 15,
    order: [[3, 'desc']],
    columnDefs: [{ targets: '_all', defaultContent: '—' }],
    language: { emptyTable: 'No attendance records yet.' }
}));
</script>
</x-app-layout>
