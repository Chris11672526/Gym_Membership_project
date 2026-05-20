@props(['floating' => false])

<button
    type="button"
    onclick="toggleFitzoneTheme()"
    class="{{ $floating ? 'fixed bottom-5 right-5 z-50 shadow-lg' : '' }} inline-flex items-center gap-2 rounded border border-white/10 bg-white/10 px-4 py-2 text-sm font-bold text-white backdrop-blur transition hover:bg-white/15"
>
    <span class="grid h-5 w-5 place-items-center rounded-full bg-red-600 text-[10px] font-black text-white">Aa</span>
    <span data-theme-label>Dark</span>
</button>
