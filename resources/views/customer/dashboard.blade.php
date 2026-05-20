<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <section class="border-b border-white/10 bg-[radial-gradient(circle_at_top_left,rgba(220,38,38,.22),transparent_34%),linear-gradient(135deg,#111827_0%,#09090b_58%,#18181b_100%)]">
            <div class="mx-auto max-w-7xl px-6 py-10">
                <p class="text-sm font-bold uppercase tracking-widest text-red-300">Overview</p>
                <div class="mt-3 flex flex-col justify-between gap-6 lg:flex-row lg:items-end">
                    <div>
                        <h1 class="text-4xl font-black md:text-5xl">Welcome, {{ $customer->first_name }}</h1>
                        <p class="mt-3 text-neutral-300">{{ $branch->name ?? 'FitZone Branch' }}{{ isset($branch->city) ? ' - '.$branch->city : '' }}</p>
                    </div>
                    <a href="{{ route('customer.payments') }}" class="w-fit rounded bg-red-600 px-5 py-3 font-bold hover:bg-red-700">View Payments</a>
                </div>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-6 py-8">
            @if (session('membership_status'))
                <div class="mb-6 rounded-lg border border-yellow-500/30 bg-yellow-950/40 px-5 py-4 font-bold text-yellow-200">
                    {{ session('membership_status') }}
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-4">
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-sm text-neutral-400">Current Plan</p>
                    <p class="mt-2 text-2xl font-black">{{ $membership->plan_name ?? 'No plan' }}</p>
                </article>
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-sm text-neutral-400">Membership Status</p>
                    <p class="mt-2 text-2xl font-black {{ ($membership->status ?? '') === 'Active' ? 'text-green-300' : 'text-red-300' }}">{{ $membership->status ?? 'N/A' }}</p>
                </article>
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-sm text-neutral-400">Days Remaining</p>
                    <p class="mt-2 text-2xl font-black">{{ $daysRemaining }}</p>
                </article>
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                    <p class="text-sm text-neutral-400">Latest Payment</p>
                    <p class="mt-2 text-2xl font-black">{{ $payments->first()->status ?? 'None' }}</p>
                </article>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-[.95fr_1.05fr]">
                <article class="rounded-lg border border-white/10 bg-neutral-900 p-6">
                    <p class="text-sm font-bold uppercase tracking-widest text-red-400">Membership</p>
                    <div class="mt-2 flex flex-col justify-between gap-4 md:flex-row md:items-start">
                        <div>
                            <h2 class="text-3xl font-black">{{ $membership->plan_name ?? 'No active plan' }}</h2>
                            <p class="mt-3 leading-7 text-neutral-400">{{ $membership->features ?? 'Choose a membership plan to start training.' }}</p>
                        </div>

                        @if (($membership->status ?? null) === 'Active')
                            <form method="POST" action="{{ route('customer.membership.cancel') }}" onsubmit="return confirm('Are you sure you want to cancel your membership?');">
                                @csrf
                                <button class="rounded border border-red-500/40 px-5 py-3 text-sm font-black text-red-300 transition hover:bg-red-600 hover:text-white">
                                    Cancel Membership
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-3">
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="text-sm text-neutral-400">Start</p>
                            <p class="mt-1 font-bold">{{ $membership->start_date ?? 'N/A' }}</p>
                        </div>
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="text-sm text-neutral-400">Expires</p>
                            <p class="mt-1 font-bold">{{ $membership->expiration_date ?? 'N/A' }}</p>
                        </div>
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="text-sm text-neutral-400">Price</p>
                            <p class="mt-1 font-bold text-red-300">{{ $membership ? 'PHP '.number_format($membership->price, 2) : 'N/A' }}</p>
                        </div>
                    </div>
                </article>

                <article class="rounded-lg border border-white/10 bg-neutral-900 p-6">
                    <p class="text-sm font-bold uppercase tracking-widest text-red-400">Workout Plan</p>
                    <h2 class="mt-2 text-3xl font-black">This week</h2>
                    <div class="mt-5 grid gap-3 md:grid-cols-3">
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="font-black">Mon / Wed / Fri</p>
                            <p class="mt-2 text-sm leading-6 text-neutral-400">Strength training and progressive weights.</p>
                        </div>
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="font-black">Tue / Thu</p>
                            <p class="mt-2 text-sm leading-6 text-neutral-400">Cardio, mobility, and core.</p>
                        </div>
                        <div class="rounded bg-neutral-950 p-4">
                            <p class="font-black">Sat / Sun</p>
                            <p class="mt-2 text-sm leading-6 text-neutral-400">Optional class on Saturday. Rest on Sunday.</p>
                        </div>
                    </div>
                </article>
            </div>

            <section class="mt-6 grid gap-4 md:grid-cols-4">
                <a href="{{ route('customer.classes') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 transition hover:border-red-500/60">
                    <p class="text-sm text-neutral-400">Classes</p>
                    <p class="mt-2 text-3xl font-black">{{ $classes->count() }}</p>
                    <p class="mt-3 text-sm text-red-300">See class schedule</p>
                </a>
                <a href="{{ route('customer.trainers') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 transition hover:border-red-500/60">
                    <p class="text-sm text-neutral-400">Trainers</p>
                    <p class="mt-2 text-3xl font-black">{{ $trainers->count() }}</p>
                    <p class="mt-3 text-sm text-red-300">Choose a coach</p>
                </a>
                <a href="{{ route('customer.equipment') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 transition hover:border-red-500/60">
                    <p class="text-sm text-neutral-400">Equipment</p>
                    <p class="mt-2 text-3xl font-black">{{ $equipment->count() }}</p>
                    <p class="mt-3 text-sm text-red-300">View branch equipment</p>
                </a>
                <a href="{{ route('customer.payments') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-5 transition hover:border-red-500/60">
                    <p class="text-sm text-neutral-400">Payments</p>
                    <p class="mt-2 text-3xl font-black">{{ $payments->count() }}</p>
                    <p class="mt-3 text-sm text-red-300">Open payment history</p>
                </a>
            </section>
        </main>
    </div>
</x-app-layout>
