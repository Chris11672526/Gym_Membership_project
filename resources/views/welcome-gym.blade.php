<x-guest-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <header class="sticky top-0 z-20 border-b border-white/10 bg-neutral-950/90 backdrop-blur">
            <div class="mx-auto flex max-w-[1500px] items-center justify-between px-8 py-5">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-12 w-12 place-items-center rounded bg-red-600 text-lg font-black">FZ</span>
                    <span>
                        <span class="block text-xl font-black leading-6">FitZone</span>
                        <span class="block text-sm text-neutral-400">Gym Membership</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-8 text-base font-bold text-neutral-300 md:flex">
                    <a href="#plans" class="hover:text-white">Plans</a>
                    <a href="#equipment" class="hover:text-white">Equipment</a>
                    <a href="#classes" class="hover:text-white">Classes</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-white">Admin</a>
                </nav>
            </div>
        </header>

        <main>
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(220,38,38,.32),transparent_34%),linear-gradient(135deg,#111827_0%,#09090b_52%,#18181b_100%)]"></div>
                <div class="relative mx-auto grid max-w-[1500px] gap-14 px-8 py-20 lg:grid-cols-[1.08fr_.92fr] lg:py-28">
                    <div class="flex flex-col justify-center">
                        <p class="mb-5 w-fit rounded bg-white/10 px-4 py-2 text-base font-bold text-red-200">Open gym, classes, trainers, and membership tracking</p>
                        <h1 class="max-w-4xl text-6xl font-black leading-tight tracking-normal md:text-8xl">Build your strongest routine with FitZone.</h1>
                        <p class="mt-7 max-w-3xl text-xl leading-9 text-neutral-300">
                            Register online, choose a monthly, quarterly, or annual membership, then view equipment, trainers, class schedules, workout days, rest days, and payments from your dashboard.
                        </p>

                        <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                            <a href="{{ route('customer.register') }}" class="rounded bg-red-600 px-8 py-4 text-center text-base font-black shadow-lg shadow-red-950/30 transition hover:bg-red-700">
                                Get Membership
                            </a>
                            <a href="{{ route('customer.login') }}" class="rounded border border-white/15 bg-white/10 px-8 py-4 text-center text-base font-black transition hover:bg-white/15">
                                Customer Login
                            </a>
                            <a href="{{ route('admin.login') }}" class="rounded border border-white/15 px-8 py-4 text-center text-base font-black text-neutral-200 transition hover:bg-white/10">
                                Admin Login
                            </a>
                        </div>

                        <div class="mt-12 grid max-w-3xl grid-cols-3 gap-5">
                            <div class="border-l-2 border-red-500 pl-4">
                                <p class="text-4xl font-black">{{ count($plans) }}</p>
                                <p class="text-base text-neutral-400">Plans</p>
                            </div>
                            <div class="border-l-2 border-red-500 pl-4">
                                <p class="text-4xl font-black">{{ count($trainers) }}</p>
                                <p class="text-base text-neutral-400">Trainers</p>
                            </div>
                            <div class="border-l-2 border-red-500 pl-4">
                                <p class="text-4xl font-black">{{ count($classes) }}</p>
                                <p class="text-base text-neutral-400">Classes</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-white/10 bg-neutral-900/80 p-6 shadow-2xl shadow-black/40">
                        <div class="aspect-[4/3] overflow-hidden rounded bg-neutral-800">
                            <img
                                src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1100&q=80"
                                alt="FitZone gym training area"
                                class="h-full w-full object-cover"
                            >
                        </div>
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded bg-neutral-800 p-5">
                                <p class="text-base text-neutral-400">Workout Days</p>
                                <p class="mt-1 text-lg font-bold">Mon to Sat</p>
                            </div>
                            <div class="rounded bg-neutral-800 p-5">
                                <p class="text-base text-neutral-400">Rest Day</p>
                                <p class="mt-1 text-lg font-bold">Sunday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="plans" class="mx-auto max-w-[1500px] px-8 py-20">
                <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
                    <div>
                        <p class="text-base font-bold uppercase tracking-widest text-red-400">Membership</p>
                        <h2 class="mt-3 text-4xl font-black md:text-5xl">Choose your training pass</h2>
                    </div>
                    <a href="{{ route('customer.register') }}" class="w-fit rounded bg-red-600 px-7 py-4 text-base font-black hover:bg-red-700">Apply Now</a>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                    @foreach ($plans as $plan)
                        <article class="rounded-lg border border-white/10 bg-neutral-900 p-7 transition hover:-translate-y-1 hover:border-red-500/60">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="text-2xl font-black">{{ $plan->name }}</h3>
                                <p class="whitespace-nowrap text-lg font-black text-red-300">PHP {{ number_format($plan->price, 2) }}</p>
                            </div>
                            <p class="mt-4 text-base leading-7 text-neutral-300">{{ $plan->description }}</p>
                            <p class="mt-5 text-base leading-7 text-neutral-400">{{ $plan->features }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="equipment" class="border-y border-white/10 bg-neutral-900/55">
                <div class="mx-auto max-w-[1500px] px-8 py-20">
                    <p class="text-base font-bold uppercase tracking-widest text-red-400">Facilities</p>
                    <h2 class="mt-3 text-4xl font-black md:text-5xl">Equipment available</h2>

                    <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($equipment as $item)
                            <article class="rounded-lg bg-neutral-950 p-7">
                                <p class="text-xl font-black">{{ $item->name }}</p>
                                <p class="mt-1 text-base text-neutral-400">{{ $item->brand ?? 'Gym equipment' }}</p>
                                <p class="mt-5 text-base text-neutral-300">Quantity: {{ $item->quantity }}</p>
                                <p class="text-base text-neutral-300">Condition: {{ $item->condition }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="classes" class="mx-auto max-w-[1500px] px-8 py-20">
                <p class="text-base font-bold uppercase tracking-widest text-red-400">Programs</p>
                <h2 class="mt-3 text-4xl font-black md:text-5xl">Classes and guided sessions</h2>

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @foreach ($classes as $class)
                        <article class="rounded-lg border border-white/10 bg-neutral-900 p-7">
                            <p class="text-2xl font-black">{{ $class->name }}</p>
                            <p class="mt-3 text-base leading-7 text-neutral-400">{{ $class->description }}</p>
                            <p class="mt-5 text-lg font-semibold text-red-200">{{ $class->schedule_day }}</p>
                            <p class="text-base text-neutral-300">{{ $class->start_time }} - {{ $class->end_time }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</x-guest-layout>
