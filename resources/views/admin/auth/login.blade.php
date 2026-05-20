<x-guest-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[.92fr_1.08fr]">
            <section class="flex items-center justify-center px-6 py-10">
                <form method="POST" action="{{ route('admin.login') }}" class="w-full max-w-md rounded-lg border border-white/10 bg-neutral-900 p-8 shadow-2xl shadow-black/40">
                    @csrf

                    <a href="{{ route('home') }}" class="mb-8 flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded bg-blue-600 font-black">FZ</span>
                        <span class="font-black">FitZone Admin</span>
                    </a>

                    <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Staff Portal</p>
                    <h1 class="mt-2 text-3xl font-black">Manage the gym floor</h1>
                    <p class="mt-2 text-sm leading-6 text-neutral-400">View registrations, active and expired memberships, sessions, payments, and reports.</p>

                    <div class="mt-8 space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-neutral-300">Admin email</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@fitzone.com" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-neutral-300">Password</label>
                            <input type="password" name="password" placeholder="Enter your password" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="mt-5 rounded border border-red-500/30 bg-red-950/50 px-4 py-3 text-sm text-red-200">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <button class="mt-6 w-full rounded bg-blue-600 py-3 font-black transition hover:bg-blue-700">
                        Login as Admin
                    </button>

                    <a href="{{ route('customer.login') }}" class="mt-6 block text-center text-sm text-neutral-400 hover:text-white">Go to customer login</a>
                </form>
            </section>

            <section class="relative hidden overflow-hidden lg:block">
                <img
                    src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=1300&q=80"
                    alt="Gym equipment"
                    class="absolute inset-0 h-full w-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-l from-neutral-950 via-neutral-950/75 to-transparent"></div>
                <div class="relative flex h-full items-end p-10">
                    <div class="max-w-xl pb-10">
                        <p class="mb-3 text-sm font-bold uppercase tracking-widest text-blue-300">Operations Dashboard</p>
                        <h2 class="text-6xl font-black leading-tight">Know every member status at a glance.</h2>
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>
