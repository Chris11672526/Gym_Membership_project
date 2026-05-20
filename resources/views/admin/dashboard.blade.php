<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Admin Overview</p>
            <h1 class="mt-2 text-4xl font-black">Gym Operations</h1>
            <p class="mt-2 text-neutral-400">Quick view of members, memberships, payments, and sessions.</p>

            <section class="mt-6 grid gap-4 md:grid-cols-3 xl:grid-cols-6">
                <a href="{{ route('admin.members') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/60">
                    <p class="text-sm text-neutral-400">Members</p>
                    <p class="mt-2 text-3xl font-black">{{ $totalCustomers }}</p>
                </a>
                <a href="{{ route('admin.members') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/60">
                    <p class="text-sm text-neutral-400">Active</p>
                    <p class="mt-2 text-3xl font-black text-green-300">{{ $activeMemberships }}</p>
                </a>
                <a href="{{ route('admin.members') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/60">
                    <p class="text-sm text-neutral-400">Expired</p>
                    <p class="mt-2 text-3xl font-black text-red-300">{{ $expiredMemberships }}</p>
                </a>
                <a href="{{ route('admin.payments') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/60">
                    <p class="text-sm text-neutral-400">Pending Pay</p>
                    <p class="mt-2 text-3xl font-black text-yellow-300">{{ $pendingPayments }}</p>
                </a>
                <a href="{{ route('admin.payments') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/60">
                    <p class="text-sm text-neutral-400">Revenue</p>
                    <p class="mt-2 text-2xl font-black">PHP {{ number_format($totalRevenue, 0) }}</p>
                </a>
                <a href="{{ route('admin.sessions') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 hover:border-blue-500/60">
                    <p class="text-sm text-neutral-400">Sessions</p>
                    <p class="mt-2 text-3xl font-black">{{ $sessionCount }}</p>
                </a>
            </section>

            <section class="mt-6 grid gap-6 lg:grid-cols-[1fr_.9fr]">
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Registrations</p>
                            <h2 class="mt-2 text-2xl font-black">Monthly trend</h2>
                        </div>
                        <a href="{{ route('admin.reports') }}" class="text-sm font-bold text-blue-300 hover:text-blue-200">Reports</a>
                    </div>
                    <div class="mt-6 h-72">
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </article>

                <article class="rounded-lg border border-white/10 bg-neutral-900 p-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-black">Latest Members</h2>
                        <a href="{{ route('admin.members') }}" class="text-sm font-bold text-blue-300 hover:text-blue-200">View all</a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ($customers as $customer)
                            <div class="rounded bg-neutral-950 p-4">
                                <p class="font-black">{{ $customer->first_name }} {{ $customer->last_name }}</p>
                                <p class="text-sm text-neutral-400">{{ $customer->email }} - {{ $customer->phone }}</p>
                            </div>
                        @endforeach
                    </div>
                </article>
            </section>

            <section class="mt-6 rounded-lg border border-white/10 bg-neutral-900 p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black">Recent Payments</h2>
                    <a href="{{ route('admin.payments') }}" class="text-sm font-bold text-blue-300 hover:text-blue-200">Open payments</a>
                </div>
                <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-5">
                    @foreach ($payments as $payment)
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="font-black">PHP {{ number_format($payment->total_paid, 2) }}</p>
                            <p class="text-sm text-neutral-400">{{ $payment->payment_method }}</p>
                            <p class="mt-2 text-sm font-bold {{ $payment->status === 'Paid' ? 'text-green-300' : 'text-yellow-300' }}">{{ $payment->status }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            new Chart(document.getElementById('registrationsChart'), {
                type: 'bar',
                data: {
                    labels: @json($monthlyLabels),
                    datasets: [{
                        label: 'Registrations',
                        data: @json($monthlyValues),
                        backgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { ticks: { color: '#d4d4d4' }, grid: { color: 'rgba(255,255,255,.08)' } },
                        y: { ticks: { color: '#d4d4d4' }, grid: { color: 'rgba(255,255,255,.08)' } }
                    },
                    plugins: { legend: { labels: { color: '#d4d4d4' } } }
                }
            });
        </script>
    </div>
</x-app-layout>
