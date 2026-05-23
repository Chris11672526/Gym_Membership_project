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

                    <form method="POST" action="{{ route('customer.register') }}">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-neutral-900 text-white @error('full_name') border-red-500 @enderror">
                            @error('full_name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-neutral-900 text-white @error('phone') border-red-500 @enderror">
                            @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-neutral-900 text-white @error('email') border-red-500 @enderror">
                            @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Branch Dropdown -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Branch</label>
                            <select name="branch_id" class="mt-1 block w-full rounded-md bg-neutral-900 text-white @error('branch_id') border-red-500 @enderror">
                                <option value="">Choose branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            @error('branch_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Membership Plan Dropdown -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Membership Plan</label>
                            <select name="membership_plan_id" class="mt-1 block w-full rounded-md bg-neutral-900 text-white @error('membership_plan_id') border-red-500 @enderror">
                                <option value="">Choose plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ old('membership_plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                                @endforeach
                            </select>
                            @error('membership_plan_id')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Password</label>
                            <input type="password" name="password"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-neutral-900 text-white @error('password') border-red-500 @enderror">
                            @error('password')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-neutral-900 text-white">
                        </div>
                        <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-300">Payment Method</label>
                                <select name="payment_method" class="mt-1 block w-full rounded-md bg-neutral-900 text-white">
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="PayMaya">PayMaya</option>
                                </select>
                            </div>
                        <button type="submit"
                                class="w-full py-3 mt-4 bg-red-600 hover:bg-red-700 rounded font-bold text-white">
                            Create Membership
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-guest-layout>