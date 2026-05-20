<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <div class="mb-6 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <p class="text-sm font-bold uppercase tracking-widest text-red-400">Payments</p>
                    <h1 class="mt-2 text-4xl font-black">Payment History</h1>
                    <p class="mt-2 text-neutral-400">Track membership payments and confirmation status.</p>
                </div>
                <div class="rounded-lg border border-white/10 bg-neutral-900 px-5 py-4">
                    <p class="text-sm text-neutral-400">Latest Status</p>
                    <p class="text-2xl font-black">{{ $payments->first()->status ?? 'No payment' }}</p>
                </div>
            </div>

            @if (session('payment_status'))
                <div class="mb-6 rounded-lg border border-green-500/30 bg-green-950/40 px-5 py-4 font-bold text-green-200">
                    {{ session('payment_status') }}
                </div>
            @endif

            @if ($payments->where('status', 'Pending')->count())
                <section class="mb-6 rounded-lg border border-yellow-500/30 bg-neutral-900 p-6">
                    <p class="text-sm font-bold uppercase tracking-widest text-yellow-300">Pending Payment</p>
                    <h2 class="mt-2 text-2xl font-black">Complete your membership payment</h2>
                    <p class="mt-2 text-neutral-400">Choose how you paid. For GCash, PayMaya, card, or bank transfer, enter your reference number.</p>

                    <div class="mt-5 grid gap-4">
                        @foreach ($payments->where('status', 'Pending') as $pendingPayment)
                            <form method="POST" action="{{ route('customer.payments.pay', $pendingPayment->id) }}" class="rounded bg-neutral-950 p-5">
                                @csrf

                                <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                                    <div>
                                        <p class="text-sm text-neutral-400">Amount to pay</p>
                                        <p class="text-3xl font-black text-yellow-200">PHP {{ number_format($pendingPayment->total_paid, 2) }}</p>
                                    </div>

                                    <label class="w-full lg:max-w-xs">
                                        <span class="mb-2 block text-sm font-semibold text-neutral-300">Payment method</span>
                                        <select name="payment_method" class="w-full rounded border border-white/10 bg-neutral-900 px-4 py-3 text-white focus:border-red-500 focus:ring-red-500">
                                            @foreach (['Cash', 'GCash', 'Credit Card', 'Bank Transfer', 'PayMaya'] as $method)
                                                <option value="{{ $method }}" @selected($pendingPayment->payment_method === $method)>{{ $method }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="w-full lg:max-w-sm">
                                        <span class="mb-2 block text-sm font-semibold text-neutral-300">Reference number</span>
                                        <input name="reference_no" value="{{ $pendingPayment->reference_no }}" placeholder="Required for online payment" class="w-full rounded border border-white/10 bg-neutral-900 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                                    </label>

                                    <button class="rounded bg-red-600 px-6 py-3 font-black text-white hover:bg-red-700">
                                        Pay Now
                                    </button>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-500/30 bg-red-950/40 px-5 py-4 text-red-200">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <section class="rounded-lg border border-white/10 bg-neutral-900">
                <div class="grid grid-cols-4 border-b border-white/10 px-5 py-3 text-sm font-bold text-neutral-400">
                    <span>Amount</span>
                    <span>Method</span>
                    <span>Date</span>
                    <span class="text-right">Status</span>
                </div>
                @forelse ($payments as $payment)
                    <div class="grid grid-cols-4 items-center border-b border-white/5 px-5 py-4 text-sm last:border-b-0">
                        <span class="font-black">PHP {{ number_format($payment->total_paid, 2) }}</span>
                        <span class="text-neutral-300">{{ $payment->payment_method }}{{ $payment->reference_no ? ' - '.$payment->reference_no : '' }}</span>
                        <span class="text-neutral-400">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</span>
                        <span class="text-right font-bold {{ $payment->status === 'Paid' ? 'text-green-300' : 'text-yellow-300' }}">{{ $payment->status }}</span>
                    </div>
                @empty
                    <p class="p-6 text-neutral-400">No payments recorded yet.</p>
                @endforelse
            </section>
        </main>
    </div>
</x-app-layout>
