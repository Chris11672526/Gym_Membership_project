<x-guest-layout>
<div class="min-h-screen bg-neutral-950 text-white">
    <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-2">

        {{-- Left: Form --}}
        <section class="flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 inline-flex items-center gap-3">
                    <span class="grid h-10 w-10 place-items-center rounded-lg bg-red-600 text-sm font-black shadow-lg shadow-red-900/40">FZ</span>
                    <span>
                        <span class="block text-sm font-black">FitZone</span>
                        <span class="block text-[10px] font-bold uppercase tracking-widest text-red-400">Member Portal</span>
                    </span>
                </a>

                <div class="rounded-2xl border border-white/8 bg-neutral-900/70 p-8 shadow-2xl shadow-black/40 backdrop-blur">
                    <p class="text-xs font-black uppercase tracking-[.2em] text-red-400">Welcome back</p>
                    <h1 class="mt-2 text-3xl font-black">Sign in to your account</h1>
                    <p class="mt-2 text-sm leading-6 text-neutral-400">Access your membership, payments, classes, and trainers.</p>

                    {{-- Alert container for AJAX responses --}}
                    <div id="login-alert" class="hidden mt-5 rounded-xl border px-4 py-3 text-sm font-semibold"></div>

                    <div class="mt-8 space-y-5">
                        <div>
                            <label class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Email Address</label>
                            <input type="email" id="login-email" placeholder="you@email.com" autocomplete="email"
                                   class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                        </div>

                        {{-- Password with eye toggle --}}
                        <div x-data="{ show: false }">
                            <label class="mb-2 block text-[10px] font-black uppercase tracking-widest text-neutral-400">Password</label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" id="login-password" placeholder="Enter your password" autocomplete="current-password"
                                       class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 pr-12 text-white placeholder:text-neutral-600 transition focus:border-red-500/70 focus:outline-none focus:ring-2 focus:ring-red-500/20">
                                <button type="button" @click="show=!show" tabindex="-1"
                                        class="absolute inset-y-0 right-0 flex items-center px-4 text-neutral-500 transition hover:text-white">
                                    <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.563-4.308m3.14-2.42A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-1.308 2.566M3 3l18 18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" id="login-remember" class="rounded border-white/20 bg-white/5 text-red-600 focus:ring-red-500/20">
                            <span class="text-sm text-neutral-400">Remember this device</span>
                        </label>

                        <button type="button" id="login-btn" onclick="submitLogin()"
                                class="w-full rounded-xl bg-red-600 py-3.5 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-red-900/30 transition hover:bg-red-500 active:scale-[.98]">
                            Sign In
                        </button>
                    </div>

                    <div class="mt-6 border-t border-white/8 pt-6 space-y-3 text-center">
                        <a href="{{ route('customer.register') }}" class="block text-sm text-neutral-500 transition hover:text-white">
                            No account yet? <span class="font-bold text-red-400">Register here</span>
                        </a>
                        <a href="{{ route('admin.login') }}" class="block text-sm text-neutral-600 transition hover:text-white">
                            Staff / Admin login →
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Right: Image --}}
        <section class="relative hidden overflow-hidden lg:block">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1300&q=80"
                 alt="Gym" class="absolute inset-0 h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-l from-neutral-950 via-neutral-950/70 to-transparent"></div>
            <div class="relative flex h-full flex-col justify-end p-12 pb-16">
                <p class="mb-4 text-xs font-black uppercase tracking-[.2em] text-red-400">FitZone Member Portal</p>
                <h2 class="text-5xl font-black leading-tight">Your fitness journey starts here.</h2>
            </div>
        </section>
    </div>
</div>

<script>
function submitLogin() {
    const btn   = document.getElementById('login-btn');
    const alert = document.getElementById('login-alert');
    const email    = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;
    const remember = document.getElementById('login-remember').checked;

    alert.className = 'hidden mt-5 rounded-xl border px-4 py-3 text-sm font-semibold';

    if (!email || !password) {
        showAlert('Please fill in all fields.', 'error');
        return;
    }

    btn.disabled   = true;
    btn.textContent = 'Signing in…';

    fetch('{{ route("customer.login") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ email, password, remember })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('Login successful! Redirecting…', 'success');
            setTimeout(() => window.location.href = data.redirect, 800);
        } else {
            showAlert(data.message || 'Invalid login details.', 'error');
            btn.disabled    = false;
            btn.textContent = 'Sign In';
        }
    })
    .catch(() => {
        showAlert('Something went wrong. Please try again.', 'error');
        btn.disabled    = false;
        btn.textContent = 'Sign In';
    });
}

function showAlert(msg, type) {
    const el = document.getElementById('login-alert');
    el.textContent = msg;
    el.className = type === 'success'
        ? 'mt-5 rounded-xl border border-green-500/30 bg-green-950/40 px-4 py-3 text-sm font-semibold text-green-300'
        : 'mt-5 rounded-xl border border-red-500/30 bg-red-950/40 px-4 py-3 text-sm font-semibold text-red-300';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Enter') submitLogin();
});
</script>
</x-guest-layout>
