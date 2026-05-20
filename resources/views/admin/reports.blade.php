<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Reports</p>
            <h1 class="mt-2 text-4xl font-black">Membership Reports</h1>
            <div class="mt-6 grid gap-4 md:grid-cols-4">
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Members</p><p class="mt-2 text-3xl font-black">{{ $totalCustomers }}</p></div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Active</p><p class="mt-2 text-3xl font-black text-green-300">{{ $activeMemberships }}</p></div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Expired</p><p class="mt-2 text-3xl font-black text-red-300">{{ $expiredMemberships }}</p></div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Revenue</p><p class="mt-2 text-2xl font-black">PHP {{ number_format($totalRevenue, 0) }}</p></div>
            </div>
            <section class="mt-6 rounded-lg border border-white/10 bg-neutral-900 p-6">
                <h2 class="text-2xl font-black">Monthly registrations</h2>
                <div class="mt-6 h-80"><canvas id="registrationsChart"></canvas></div>
            </section>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('registrationsChart'), {
                type: 'line',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{ label: 'Registrations', data: @json($monthlyValues), borderColor: '#60a5fa', backgroundColor: 'rgba(96,165,250,.25)', tension: .3, fill: true }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { x: { ticks: { color: '#d4d4d4' } }, y: { ticks: { color: '#d4d4d4' } } }, plugins: { legend: { labels: { color: '#d4d4d4' } } } }
            });
        </script>
    </div>
</x-app-layout>
