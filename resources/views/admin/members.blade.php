<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Members</p>
            <h1 class="mt-2 text-4xl font-black">Member Records</h1>
            <section class="mt-6 overflow-hidden rounded-lg border border-white/10 bg-neutral-900">
                <div class="grid grid-cols-5 border-b border-white/10 px-5 py-3 text-sm font-bold text-neutral-400">
                    <span>Name</span><span>Email</span><span>Phone</span><span>Plan</span><span class="text-right">Status</span>
                </div>
                @foreach ($customers as $customer)
                    <div class="grid grid-cols-5 items-center border-b border-white/5 px-5 py-4 text-sm last:border-b-0">
                        <span class="font-bold">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                        <span class="text-neutral-400">{{ $customer->email }}</span>
                        <span class="text-neutral-400">{{ $customer->phone }}</span>
                        <span>{{ $customer->plan_name ?? 'No plan' }}</span>
                        <span class="text-right font-bold {{ ($customer->membership_status ?? $customer->status) === 'Active' ? 'text-green-300' : 'text-red-300' }}">{{ $customer->membership_status ?? $customer->status }}</span>
                    </div>
                @endforeach
            </section>
        </main>
    </div>
</x-app-layout>
