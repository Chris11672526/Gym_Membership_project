<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-red-400">Trainers</p>
            <h1 class="mt-2 text-4xl font-black">Available Coaches</h1>
            <p class="mt-2 text-neutral-400">Find a trainer based on your training goals.</p>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @forelse ($trainers as $trainer)
                    <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                        <div class="flex items-start gap-4">
                            <div class="grid h-14 w-14 shrink-0 place-items-center rounded bg-red-600 text-lg font-black">
                                {{ strtoupper(substr($trainer->first_name, 0, 1).substr($trainer->last_name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xl font-black">{{ $trainer->first_name }} {{ $trainer->last_name }}</p>
                                <p class="mt-1 text-sm text-neutral-400">{{ $trainer->specialization }}</p>
                            </div>
                        </div>
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            <div class="rounded bg-neutral-950 p-3">
                                <p class="text-sm text-neutral-400">Experience</p>
                                <p class="mt-1 font-bold">{{ $trainer->experience_years }} years</p>
                            </div>
                            <div class="rounded bg-neutral-950 p-3">
                                <p class="text-sm text-neutral-400">Status</p>
                                <p class="mt-1 font-bold text-green-300">{{ $trainer->status }}</p>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="rounded-lg border border-white/10 bg-neutral-900 p-6 text-neutral-400">No active trainers listed yet.</p>
                @endforelse
            </div>
        </main>
    </div>
</x-app-layout>
