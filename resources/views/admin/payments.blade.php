<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Payments</p>
            <h1 class="mt-2 text-4xl font-black">Payment Management</h1>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Paid Revenue</p><p class="mt-2 text-3xl font-black">PHP {{ number_format($totalRevenue, 2) }}</p></div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Pending</p><p class="mt-2 text-3xl font-black text-yellow-300">{{ $pendingPayments }}</p></div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 p-5"><p class="text-sm text-neutral-400">Records</p><p class="mt-2 text-3xl font-black">{{ $payments->count() }}</p></div>
            </div>
            <section class="mt-6 overflow-hidden rounded-lg border border-white/10 bg-neutral-900">
                <div class="grid grid-cols-5 border-b border-white/10 px-5 py-3 text-sm font-bold text-neutral-400">
                    <span>Member</span><span>Amount</span><span>Method</span><span>Date</span><span class="text-right">Status</span>
                </div>
                @foreach ($payments as $payment)
                    <div class="grid grid-cols-5 items-center border-b border-white/5 px-5 py-4 text-sm last:border-b-0">
                        <span class="font-bold">{{ $payment->first_name }} {{ $payment->last_name }}</span>
                        <span>PHP {{ number_format($payment->total_paid, 2) }}</span>
                        <span class="text-neutral-400">{{ $payment->payment_method }}</span>
                        <span class="text-neutral-400">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                        <span class="text-right font-bold {{ $payment->status === 'Paid' ? 'text-green-300' : 'text-yellow-300' }}">{{ $payment->status }}</span>
                    </div>
                @endforeach
            </section>
        </main>
    </div>
</x-app-layout>
