<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-red-400">Equipment</p>
            <h1 class="mt-2 text-4xl font-black">Branch Equipment</h1>
            <p class="mt-2 text-neutral-400">What you can use at {{ $branch->name ?? 'your branch' }}.</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @forelse ($equipment as $item)
                    <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                        <p class="text-lg font-black">{{ $item->name }}</p>
                        <p class="mt-1 text-sm text-neutral-400">{{ $item->brand ?? 'Gym equipment' }}</p>
                        <div class="mt-5 flex items-center justify-between rounded bg-neutral-950 p-3 text-sm">
                            <span>Qty {{ $item->quantity }}</span>
                            <span class="{{ $item->condition === 'Good' || $item->condition === 'New' ? 'text-green-300' : 'text-yellow-300' }}">{{ $item->condition }}</span>
                        </div>
                    </article>
                @empty
                    <p class="rounded-lg border border-white/10 bg-neutral-900 p-6 text-neutral-400">No equipment listed yet.</p>
                @endforelse
            </div>
        </main>
    </div>
</x-app-layout>
