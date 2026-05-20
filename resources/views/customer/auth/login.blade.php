<x-guest-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[1.05fr_.95fr]">
            <section class="relative hidden overflow-hidden lg:block">
                <img
                    src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=1300&q=80"
                    alt="Member lifting weights"
                    class="absolute inset-0 h-full w-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/75 to-transparent"></div>
                <div class="relative flex h-full flex-col justify-between p-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded bg-red-600 font-black">FZ</span>
                        <span class="font-black">FitZone</span>
                    </a>
                    <div class="max-w-xl pb-10">
                        <p class="mb-3 text-sm font-bold uppercase tracking-widest text-red-300">Member Access</p>
                        <h1 class="text-6xl font-black leading-tight">Your training life in one dashboard.</h1>
                        <p class="mt-5 text-lg leading-8 text-neutral-300">Check your membership, payments, class schedules, trainers, equipment, workout days, and rest days.</p>
                    </div>
                </div>
            </section>

            <section class="flex items-center justify-center px-6 py-10">
                <form method="POST" action="{{ route('customer.login') }}" class="w-full max-w-md rounded-lg border border-white/10 bg-neutral-900 p-8 shadow-2xl shadow-black/40">
                    @csrf

                    <a href="{{ route('home') }}" class="mb-8 flex items-center gap-3 lg:hidden">
                        <span class="grid h-10 w-10 place-items-center rounded bg-red-600 font-black">FZ</span>
                        <span class="font-black">FitZone</span>
                    </a>

                    <p class="text-sm font-bold uppercase tracking-widest text-red-400">Customer Login</p>
                    <h2 class="mt-2 text-3xl font-black">Welcome back</h2>
                    <p class="mt-2 text-sm leading-6 text-neutral-400">Sign in to continue your membership and training progress.</p>

                    <div class="mt-8 space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-neutral-300">Email address</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-neutral-300">Password</label>
                            <input type="password" name="password" placeholder="Enter your password" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-5 rounded border border-red-500/30 bg-red-950/50 px-4 py-3 text-sm text-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button class="mt-6 w-full rounded bg-red-600 py-3 font-black transition hover:bg-red-700">
                        Login
                    </button>

                    <div class="mt-6 flex items-center justify-between text-sm">
                        <a href="{{ route('customer.register') }}" class="font-semibold text-red-300 hover:text-red-200">Apply for membership</a>
                        <a href="{{ route('admin.login') }}" class="text-neutral-400 hover:text-white">Admin login</a>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-guest-layout>
