<x-guest-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[.9fr_1.1fr]">
            <section class="relative hidden overflow-hidden lg:block">
                <img
                    src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=1300&q=80"
                    alt="FitZone training area"
                    class="absolute inset-0 h-full w-full object-cover"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-neutral-950 via-neutral-950/80 to-neutral-950/20"></div>
                <div class="relative flex h-full flex-col justify-between p-10">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <span class="grid h-10 w-10 place-items-center rounded bg-red-600 font-black">FZ</span>
                        <span class="font-black">FitZone</span>
                    </a>

                    <div class="max-w-xl pb-10">
                        <p class="mb-3 text-sm font-bold uppercase tracking-widest text-red-300">Quick Membership</p>
                        <h1 class="text-6xl font-black leading-tight">Start training in minutes.</h1>
                        <p class="mt-5 text-lg leading-8 text-neutral-300">
                            Create your account, choose a branch and plan, then finish payment confirmation with the staff.
                        </p>
                    </div>
                </div>
            </section>

            <section class="flex items-center justify-center px-6 py-10">
                <div class="w-full max-w-2xl">
                    <a href="{{ route('home') }}" class="mb-8 flex items-center gap-3 lg:hidden">
                        <span class="grid h-10 w-10 place-items-center rounded bg-red-600 font-black">FZ</span>
                        <span class="font-black">FitZone</span>
                    </a>

                    <div class="mb-8">
                        <p class="text-sm font-bold uppercase tracking-widest text-red-400">Membership Application</p>
                        <h2 class="mt-2 text-4xl font-black">Join FitZone</h2>
                        <p class="mt-2 text-neutral-400">Only the essentials. You can complete your profile later.</p>
                    </div>

                    <form method="POST" action="{{ route('customer.register') }}" class="rounded-lg border border-white/10 bg-neutral-900 p-6 shadow-2xl shadow-black/40">
                        @csrf

                        <div class="grid gap-4 md:grid-cols-2">
                            <label class="md:col-span-2">
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Full name</span>
                                <input name="full_name" value="{{ old('full_name') }}" placeholder="Juan Dela Cruz" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Phone number</span>
                                <input name="phone" value="{{ old('phone') }}" placeholder="09XXXXXXXXX" maxlength="11" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Email address</span>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Branch</span>
                                <select name="branch_id" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white focus:border-red-500 focus:ring-red-500">
                                    <option value="">Choose branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                                            {{ $branch->name }} - {{ $branch->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Membership plan</span>
                                <select name="membership_plan_id" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white focus:border-red-500 focus:ring-red-500">
                                    <option value="">Choose plan</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" @selected(old('membership_plan_id') == $plan->id)>
                                            {{ $plan->name }} - PHP {{ number_format($plan->price, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Payment method</span>
                                <select name="payment_method" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white focus:border-red-500 focus:ring-red-500">
                                    <option value="">Choose payment</option>
                                    @foreach (['Cash', 'GCash', 'Credit Card', 'Bank Transfer', 'PayMaya'] as $method)
                                        <option value="{{ $method }}" @selected(old('payment_method') == $method)>{{ $method }}</option>
                                    @endforeach
                                </select>
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Reference number</span>
                                <input name="reference_no" value="{{ old('reference_no') }}" placeholder="Optional for online payment" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Password</span>
                                <input type="password" name="password" placeholder="At least 8 characters" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-neutral-300">Confirm password</span>
                                <input type="password" name="password_confirmation" placeholder="Repeat password" class="w-full rounded border border-white/10 bg-neutral-950 px-4 py-3 text-white placeholder:text-neutral-600 focus:border-red-500 focus:ring-red-500">
                            </label>
                        </div>

                        @if ($errors->any())
                            <div class="mt-5 rounded border border-red-500/30 bg-red-950/50 px-4 py-3 text-sm text-red-200">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <button class="mt-6 w-full rounded bg-red-600 py-3 font-black transition hover:bg-red-700">
                            Create Membership
                        </button>

                        <p class="mt-5 text-center text-sm text-neutral-400">
                            Already registered?
                            <a href="{{ route('customer.login') }}" class="font-semibold text-red-300 hover:text-red-200">Login here</a>
                        </p>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>
