@php
    $isAdmin = in_array(Auth::user()->role?->name, ['admin', 'staff']);
    $dashboardRoute = $isAdmin ? route('admin.dashboard') : route('customer.dashboard');
    $customerLinks = [
        ['label' => 'Overview', 'route' => 'customer.dashboard'],
        ['label' => 'Payments', 'route' => 'customer.payments'],
        ['label' => 'Classes', 'route' => 'customer.classes'],
        ['label' => 'Equipment', 'route' => 'customer.equipment'],
        ['label' => 'Trainers', 'route' => 'customer.trainers'],
    ];
    $adminLinks = [
        ['label' => 'Overview', 'route' => 'admin.dashboard'],
        ['label' => 'Members', 'route' => 'admin.members'],
        ['label' => 'Payments', 'route' => 'admin.payments'],
        ['label' => 'Sessions', 'route' => 'admin.sessions'],
        ['label' => 'Equipment', 'route' => 'admin.equipment'],
        ['label' => 'Reports', 'route' => 'admin.reports'],
    ];
@endphp

<nav x-data="{ open: false }" class="sticky top-0 z-30 border-b border-white/10 bg-neutral-950/95 text-white backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
        <div class="flex items-center gap-8">
            <a href="{{ $dashboardRoute }}" class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded bg-red-600 font-black">FZ</span>
                <span class="hidden leading-5 sm:block">
                    <span class="block font-black">FitZone</span>
                    <span class="block text-xs text-neutral-400">{{ $isAdmin ? 'Admin Portal' : 'Member Portal' }}</span>
                </span>
            </a>

            <div class="hidden items-center gap-2 lg:flex">
                @if ($isAdmin)
                    @foreach ($adminLinks as $link)
                        <a href="{{ route($link['route']) }}" class="rounded px-3 py-2 text-sm font-bold {{ request()->routeIs($link['route']) ? 'bg-blue-600 text-white' : 'text-neutral-300 hover:bg-white/10 hover:text-white' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                @else
                    @foreach ($customerLinks as $link)
                        <a href="{{ route($link['route']) }}" class="rounded px-3 py-2 text-sm font-bold {{ request()->routeIs($link['route']) ? 'bg-red-600 text-white' : 'text-neutral-300 hover:bg-white/10 hover:text-white' }}">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="hidden items-center gap-4 sm:flex">
            <x-theme-toggle />
            <span class="text-sm text-neutral-300">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded border border-white/10 px-4 py-2 text-sm font-bold text-neutral-300 hover:bg-white/10 hover:text-white">
                    Logout
                </button>
            </form>
        </div>

        <button @click="open = ! open" class="rounded border border-white/10 p-2 text-neutral-300 lg:hidden">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>
    </div>

    <div x-show="open" x-cloak class="border-t border-white/10 px-6 py-4 lg:hidden">
        <div class="grid gap-2">
            @if ($isAdmin)
                @foreach ($adminLinks as $link)
                    <a href="{{ route($link['route']) }}" class="rounded px-3 py-2 text-sm font-bold {{ request()->routeIs($link['route']) ? 'bg-blue-600 text-white' : 'text-neutral-300 hover:bg-white/10' }}">{{ $link['label'] }}</a>
                @endforeach
            @else
                @foreach ($customerLinks as $link)
                    <a href="{{ route($link['route']) }}" class="rounded px-3 py-2 text-sm font-bold {{ request()->routeIs($link['route']) ? 'bg-red-600 text-white' : 'text-neutral-300 hover:bg-white/10' }}">{{ $link['label'] }}</a>
                @endforeach
            @endif

            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button class="w-full rounded border border-white/10 px-3 py-2 text-left text-sm font-bold text-neutral-300">
                    Logout
                </button>
            </form>

            <div class="pt-2">
                <x-theme-toggle />
            </div>
        </div>
    </div>
</nav>
