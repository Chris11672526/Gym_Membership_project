<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Equipment</p>
            <h1 class="mt-2 text-4xl font-black">Equipment Inventory</h1>
            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($equipment as $item)
                    <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                        <p class="text-lg font-black">{{ $item->name }}</p>
                        <p class="text-sm text-neutral-400">{{ $item->category_name }} - {{ $item->branch_name }}</p>
                        <div class="mt-4 flex items-center justify-between rounded bg-neutral-950 p-3 text-sm">
                            <span>Qty {{ $item->quantity }}</span>
                            <span class="{{ in_array($item->condition, ['Good', 'New']) ? 'text-green-300' : 'text-yellow-300' }}">{{ $item->condition }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </main>
    </div>
</x-app-layout>
