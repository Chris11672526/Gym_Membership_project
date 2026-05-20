<x-app-layout>
    <div class="min-h-screen bg-neutral-950 text-white">
        <main class="mx-auto max-w-7xl px-6 py-8">
            <p class="text-sm font-bold uppercase tracking-widest text-blue-300">Sessions</p>
            <h1 class="mt-2 text-4xl font-black">Attendance and Class Sessions</h1>
            <section class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($attendance as $session)
                    <article class="rounded-lg border border-white/10 bg-neutral-900 p-5">
                        <p class="font-black">{{ $session->first_name }} {{ $session->last_name }}</p>
                        <p class="mt-1 text-sm text-neutral-400">{{ $session->branch_name }}</p>
                        <div class="mt-4 rounded bg-neutral-950 p-4 text-sm">
                            <p>Class: <span class="font-bold">{{ $session->class_name ?? 'Open Gym' }}</span></p>
                            <p class="mt-1 text-neutral-400">In: {{ \Carbon\Carbon::parse($session->check_in)->format('M d, Y h:i A') }}</p>
                            <p class="text-neutral-400">Out: {{ $session->check_out ? \Carbon\Carbon::parse($session->check_out)->format('h:i A') : 'Still checked in' }}</p>
                        </div>
                    </article>
                @endforeach
            </section>
        </main>
    </div>
</x-app-layout>
