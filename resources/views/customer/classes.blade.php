<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-red-400">Classes</p>
            <h1 class="mt-2 text-4xl font-black">Class Schedule</h1>
            <p class="mt-2 text-neutral-400">Available guided sessions at {{ $branch->name ?? 'your branch' }}.</p>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($classes as $class)
                    <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                        <p class="text-xl font-black">{{ $class->name }}</p>
                        <p class="mt-2 min-h-12 text-sm leading-6 text-neutral-400">{{ $class->description }}</p>
                        <div class="mt-5 rounded bg-neutral-950 p-4">
                            <p class="font-bold text-red-200">{{ $class->schedule_day }}</p>
                            <p class="mt-1 text-sm text-neutral-300">{{ substr($class->start_time, 0, 5) }} - {{ substr($class->end_time, 0, 5) }}</p>
                            <p class="mt-2 text-sm text-neutral-400">Capacity: {{ $class->max_capacity }} members</p>
                        </div>
                    </article>
                @empty
                    <p class="rounded-lg border border-white/10 bg-neutral-900 p-6 text-neutral-400">No classes listed yet.</p>
                @endforelse
            </div>
        </main>
    </div>
</x-app-layout>
