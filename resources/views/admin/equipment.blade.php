<x-app-layout>
<div class="min-h-screen bg-neutral-950 text-white">

    <section class="relative overflow-hidden border-b border-white/5">
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_60%_40%_at_100%_0%,rgba(37,99,235,.1),transparent)]"></div>
        <div class="relative mx-auto max-w-7xl px-6 py-10">
            <p class="text-xs font-black uppercase tracking-[.2em] text-blue-400">Facility</p>
            <div class="mt-3 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h1 class="text-4xl font-black">Equipment Inventory</h1>
                    <p class="mt-2 text-neutral-400">{{ count($equipment) }} items tracked. Click Edit to update quantity or condition.</p>
                </div>
                <div class="flex gap-3">
                    <div class="rounded-xl border border-white/8 bg-neutral-900/70 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-neutral-500">Total Items</p>
                        <p class="mt-1 text-xl font-black">{{ count($equipment) }}</p>
                    </div>
                    <div class="rounded-xl border border-green-500/20 bg-green-950/20 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-green-600">Good/New</p>
                        <p class="mt-1 text-xl font-black text-green-300">
                            {{ collect($equipment)->whereIn('condition', ['Good','New'])->count() }}
                        </p>
                    </div>
                    <div class="rounded-xl border border-yellow-500/20 bg-yellow-950/20 px-5 py-3 text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-yellow-600">Needs Attention</p>
                        <p class="mt-1 text-xl font-black text-yellow-300">
                            {{ collect($equipment)->whereNotIn('condition', ['Good','New'])->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Alert --}}
    <div id="eq-alert" class="hidden mx-auto max-w-7xl px-6 mt-4">
        <div id="eq-alert-inner" class="rounded-xl border px-5 py-3 text-sm font-bold"></div>
    </div>

    <main class="mx-auto max-w-7xl px-6 py-8">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($equipment as $item)
                @php
                    $conditionColor = match($item->condition) {
                        'New'          => 'text-blue-300',
                        'Good'         => 'text-green-300',
                        'Fair'         => 'text-yellow-300',
                        'Needs Repair' => 'text-orange-300',
                        'Retired'      => 'text-red-300',
                        default        => 'text-neutral-400',
                    };
                @endphp

                <article class="rounded-xl border border-white/8 bg-neutral-900/70 backdrop-blur"
                         id="equip-card-{{ $item->id }}">

                    {{-- Card info --}}
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h2 class="font-black leading-tight">{{ $item->name }}</h2>
                                <p class="mt-0.5 text-xs text-neutral-500">{{ $item->category_name }} · {{ $item->branch_name }}</p>
                            </div>
                            <span class="shrink-0 text-xs font-black {{ $conditionColor }}" id="condition-label-{{ $item->id }}">
                                {{ $item->condition }}
                            </span>
                        </div>

                        {{-- Quantity display --}}
                        <div class="mt-4 flex items-center justify-between rounded-lg bg-neutral-950/70 px-4 py-3">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-neutral-600">Quantity</p>
                                <p class="mt-1 text-2xl font-black" id="qty-display-{{ $item->id }}">{{ $item->quantity }}</p>
                            </div>
                            <button onclick="toggleEdit({{ $item->id }})"
                                    class="rounded-lg border border-blue-500/30 px-3 py-1.5 text-xs font-black text-blue-400 transition hover:bg-blue-600/10"
                                    id="edit-btn-{{ $item->id }}">
                                Edit
                            </button>
                        </div>
                    </div>

                    {{-- Edit panel (hidden by default) --}}
                    <div id="edit-panel-{{ $item->id }}" class="hidden border-t border-blue-500/20 bg-blue-950/10 p-5">
                        <p class="mb-3 text-xs font-black uppercase tracking-widest text-blue-400">Update Equipment</p>

                        {{-- Quantity controls --}}
                        <div class="mb-3">
                            <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Quantity</label>
                            <div class="flex items-center gap-2">
                                <button type="button"
                                        onclick="changeQty({{ $item->id }}, -1)"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-neutral-900 text-xl font-black text-white transition hover:bg-white/10">
                                    −
                                </button>
                                <input type="number"
                                       id="qty-input-{{ $item->id }}"
                                       value="{{ $item->quantity }}"
                                       min="0"
                                       class="w-full rounded-lg border border-white/10 bg-neutral-900 px-3 py-2 text-center font-black text-white focus:border-blue-500/70 focus:outline-none">
                                <button type="button"
                                        onclick="changeQty({{ $item->id }}, 1)"
                                        class="flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 bg-neutral-900 text-xl font-black text-white transition hover:bg-white/10">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Condition --}}
                        <div class="mb-4">
                            <label class="mb-1.5 block text-[10px] font-black uppercase tracking-widest text-neutral-500">Condition</label>
                            <select id="condition-input-{{ $item->id }}"
                                    class="w-full rounded-lg border border-white/10 bg-neutral-900 px-3 py-2.5 text-sm text-white focus:border-blue-500/70 focus:outline-none">
                                @foreach (['New','Good','Fair','Needs Repair','Retired'] as $cond)
                                    <option value="{{ $cond }}" {{ $item->condition === $cond ? 'selected' : '' }}>{{ $cond }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="button"
                                    onclick="saveEquipment({{ $item->id }})"
                                    id="save-btn-{{ $item->id }}"
                                    class="flex-1 rounded-lg bg-blue-600 py-2.5 text-sm font-black text-white transition hover:bg-blue-500 active:scale-95">
                                Save
                            </button>
                            <button type="button"
                                    onclick="toggleEdit({{ $item->id }})"
                                    class="rounded-lg border border-white/10 px-4 py-2.5 text-sm font-black text-neutral-400 transition hover:text-white">
                                Cancel
                            </button>
                        </div>
                    </div>

                    {{-- Saved confirmation --}}
                    <div id="saved-{{ $item->id }}" class="hidden border-t border-green-500/20 bg-green-950/20 px-5 py-3 text-xs font-bold text-green-300">
                        ✓ Updated successfully
                    </div>
                </article>
            @endforeach
        </div>
    </main>
</div>

<script>
const CSRF = '{{ csrf_token() }}';

function toggleEdit(id) {
    const panel  = document.getElementById('edit-panel-' + id);
    const btn    = document.getElementById('edit-btn-' + id);
    const isOpen = !panel.classList.contains('hidden');
    panel.classList.toggle('hidden');
    btn.textContent = isOpen ? 'Edit' : 'Cancel';
    btn.className   = isOpen
        ? 'rounded-lg border border-blue-500/30 px-3 py-1.5 text-xs font-black text-blue-400 transition hover:bg-blue-600/10'
        : 'rounded-lg border border-red-500/30 px-3 py-1.5 text-xs font-black text-red-400 transition hover:bg-red-600/10';
}

function changeQty(id, delta) {
    const input = document.getElementById('qty-input-' + id);
    input.value = Math.max(0, parseInt(input.value || 0) + delta);
}

function saveEquipment(id) {
    const qty       = document.getElementById('qty-input-' + id).value;
    const condition = document.getElementById('condition-input-' + id).value;
    const btn       = document.getElementById('save-btn-' + id);

    btn.disabled    = true;
    btn.textContent = 'Saving…';

    fetch('/admin/equipment/' + id + '/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ quantity: qty, condition: condition })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled    = false;
        btn.textContent = 'Save';

        if (data.success) {
            // Update display without page reload
            document.getElementById('qty-display-' + id).textContent  = qty;
            document.getElementById('condition-label-' + id).textContent = condition;

            // Toggle panel closed
            toggleEdit(id);

            // Show saved confirmation briefly
            const saved = document.getElementById('saved-' + id);
            saved.classList.remove('hidden');
            setTimeout(() => saved.classList.add('hidden'), 2500);

            showAlert('Equipment updated successfully.', 'success');
        } else {
            showAlert(data.message || 'Failed to update.', 'error');
        }
    })
    .catch(() => {
        btn.disabled    = false;
        btn.textContent = 'Save';
        showAlert('Server error. Please try again.', 'error');
    });
}

function showAlert(msg, type) {
    const wrap  = document.getElementById('eq-alert');
    const inner = document.getElementById('eq-alert-inner');
    inner.textContent = msg;
    inner.className   = type === 'success'
        ? 'rounded-xl border border-green-500/30 bg-green-950/40 px-5 py-3 text-sm font-bold text-green-300'
        : 'rounded-xl border border-red-500/30 bg-red-950/40 px-5 py-3 text-sm font-bold text-red-300';
    wrap.className = 'mx-auto max-w-7xl px-6 mt-4';
    setTimeout(() => wrap.className = 'hidden mx-auto max-w-7xl px-6 mt-4', 3000);
}
</script>
</x-app-layout>
