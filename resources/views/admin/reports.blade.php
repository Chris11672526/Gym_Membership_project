<x-app-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    <section class="relative overflow-hidden border-b border-white/5">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_50%_-20%,rgba(37,99,235,.1),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-10">
            <p class="text-xs font-black uppercase tracking-[.2em] text-blue-400">Analytics</p>
            <h1 class="mt-2 text-4xl font-black">Membership Reports</h1>
            <p class="mt-2 text-neutral-400">Overview of registrations, membership status, and revenue.</p>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-6 py-8">

        {{-- Stat cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-white/8 bg-neutral-900/70 p-5 backdrop-blur">
                <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Total Members</p>
                <p class="mt-3 text-3xl font-black">{{ $totalCustomers }}</p>
            </div>
            <div class="rounded-xl border border-green-500/20 bg-green-950/10 p-5 backdrop-blur">
                <p class="text-[10px] font-black uppercase tracking-widest text-green-600">Active</p>
                <p class="mt-3 text-3xl font-black text-green-300">{{ $activeMemberships }}</p>
            </div>
            <div class="rounded-xl border border-red-500/20 bg-red-950/10 p-5 backdrop-blur">
                <p class="text-[10px] font-black uppercase tracking-widest text-red-600">Expired</p>
                <p class="mt-3 text-3xl font-black text-red-300">{{ $expiredMemberships }}</p>
            </div>
            <div class="rounded-xl border border-blue-500/20 bg-blue-950/10 p-5 backdrop-blur">
                <p class="text-[10px] font-black uppercase tracking-widest text-blue-600">Revenue</p>
                <p class="mt-3 text-2xl font-black text-blue-200">PHP {{ number_format($totalRevenue, 0) }}</p>
            </div>
        </div>

        {{-- Charts --}}
        <div class="mt-6 grid gap-6 lg:grid-cols-2">

            {{-- Bar chart: Monthly registrations --}}
            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur">
                <p class="text-xs font-black uppercase tracking-widest text-blue-400">Monthly Registrations</p>
                <h2 class="mt-1 text-xl font-black">New Members per Month</h2>
                <div class="mt-6 h-72">
                    <canvas id="barChart"></canvas>
                </div>
            </article>

            {{-- Doughnut: Membership status breakdown --}}
            <article class="rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur">
                <p class="text-xs font-black uppercase tracking-widest text-blue-400">Membership Status</p>
                <h2 class="mt-1 text-xl font-black">Breakdown</h2>
                <div class="mt-6 flex items-center justify-center" style="height:288px">
                    <canvas id="doughnutChart" style="max-height:260px;max-width:260px"></canvas>
                </div>

                {{-- Legend --}}
                <div class="mt-4 grid grid-cols-2 gap-2">
                    @foreach ($membershipBreakdown as $row)
                        @php
                            $dot = match($row->status) {
                                'Active'    => 'bg-green-400',
                                'Expired'   => 'bg-red-400',
                                'Cancelled' => 'bg-yellow-400',
                                'Frozen'    => 'bg-blue-400',
                                default     => 'bg-neutral-400',
                            };
                        @endphp
                        <div class="flex items-center gap-2 rounded-lg bg-neutral-950/70 px-3 py-2">
                            <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span>
                            <span class="text-xs font-bold">{{ $row->status }}</span>
                            <span class="ml-auto text-xs font-black text-neutral-400">{{ $row->total }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        {{-- Line chart --}}
        <article class="mt-6 rounded-xl border border-white/8 bg-neutral-900/70 p-7 backdrop-blur">
            <p class="text-xs font-black uppercase tracking-widest text-blue-400">Registration Trend</p>
            <h2 class="mt-1 text-xl font-black">Growth Over Time</h2>
            <div class="mt-6 h-64">
                <canvas id="lineChart"></canvas>
            </div>
        </article>

        {{-- Pending payments table --}}
        <article class="mt-6 rounded-xl border border-white/8 bg-neutral-900/70 backdrop-blur overflow-hidden">
            <div class="border-b border-white/8 px-6 py-4">
                <p class="text-xs font-black uppercase tracking-widest text-yellow-400">Pending Payments</p>
                <h2 class="mt-1 text-lg font-black">{{ $pendingPayments }} payment(s) awaiting confirmation</h2>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="rounded-lg bg-neutral-950/70 p-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Total Sessions</p>
                        <p class="mt-2 text-2xl font-black">{{ $sessionCount }}</p>
                    </div>
                    <div class="rounded-lg bg-neutral-950/70 p-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Pending Payments</p>
                        <p class="mt-2 text-2xl font-black text-yellow-300">{{ $pendingPayments }}</p>
                    </div>
                    <div class="rounded-lg bg-neutral-950/70 p-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Total Revenue</p>
                        <p class="mt-2 text-xl font-black text-blue-200">PHP {{ number_format($totalRevenue, 0) }}</p>
                    </div>
                </div>
            </div>
        </article>

    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($monthlyLabels);
    const values = @json($monthlyValues);
    const gridColor = 'rgba(255,255,255,.04)';
    const tickColor = '#71717a';

    // Bar chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'New Members',
                data: values,
                backgroundColor: 'rgba(37,99,235,.7)',
                borderColor: 'rgba(96,165,250,.8)',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: tickColor, font: { size: 10 } }, grid: { color: gridColor } },
                y: { beginAtZero: true, ticks: { color: tickColor, stepSize: 1 }, grid: { color: gridColor } }
            },
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#18181b', titleColor: '#fff', bodyColor: '#a1a1aa', cornerRadius: 8, padding: 10 } }
        }
    });

    // Doughnut chart
    const breakdown = @json($membershipBreakdown);
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: breakdown.map(r => r.status),
            datasets: [{
                data: breakdown.map(r => r.total),
                backgroundColor: ['rgba(74,222,128,.8)','rgba(248,113,113,.8)','rgba(251,191,36,.8)','rgba(96,165,250,.8)'],
                borderColor: '#09090b',
                borderWidth: 3,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false }, tooltip: { backgroundColor: '#18181b', titleColor: '#fff', bodyColor: '#a1a1aa', cornerRadius: 8, padding: 10 } }
        }
    });

    // Line chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Registrations',
                data: values,
                borderColor: 'rgba(96,165,250,.9)',
                backgroundColor: 'rgba(37,99,235,.12)',
                pointBackgroundColor: 'rgba(96,165,250,1)',
                pointRadius: 5,
                tension: 0.35,
                fill: true,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                x: { ticks: { color: tickColor, font: { size: 10 } }, grid: { color: gridColor } },
                y: { beginAtZero: true, ticks: { color: tickColor, stepSize: 1 }, grid: { color: gridColor } }
            },
            plugins: { legend: { labels: { color: '#a1a1aa' } }, tooltip: { backgroundColor: '#18181b', titleColor: '#fff', bodyColor: '#a1a1aa', cornerRadius: 8, padding: 10 } }
        }
    });
</script>
</x-app-layout>
