<script>
    (function () {
        const savedTheme = localStorage.getItem('fitzone-theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();
</script>

<style>
    [x-cloak] { display: none !important; }

    html[data-theme="light"] body {
        background: #f4f4f5;
        color: #18181b;
    }

    html[data-theme="light"] .bg-neutral-950,
    html[data-theme="light"] .bg-neutral-950\/90,
    html[data-theme="light"] .bg-neutral-950\/95,
    html[data-theme="light"] .bg-zinc-950,
    html[data-theme="light"] .bg-slate-950,
    html[data-theme="light"] .bg-gray-900,
    html[data-theme="light"] .dark\:bg-gray-900 {
        background-color: #f4f4f5 !important;
    }

    html[data-theme="light"] .bg-neutral-900,
    html[data-theme="light"] .bg-neutral-900\/80,
    html[data-theme="light"] .bg-neutral-900\/55,
    html[data-theme="light"] .bg-zinc-900,
    html[data-theme="light"] .bg-slate-900,
    html[data-theme="light"] .dark\:bg-gray-800 {
        background-color: #ffffff !important;
    }

    html[data-theme="light"] .bg-neutral-800,
    html[data-theme="light"] .bg-zinc-800,
    html[data-theme="light"] .bg-white\/10 {
        background-color: #f1f5f9 !important;
    }

    html[data-theme="light"] [class*="bg-\[radial-gradient"],
    html[data-theme="light"] [class*="bg-\[linear-gradient"] {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 55%, #eef2ff 100%) !important;
    }

    html[data-theme="light"] .text-white,
    html[data-theme="light"] .dark\:text-gray-200 {
        color: #18181b !important;
    }

    html[data-theme="light"] .bg-red-600.text-white,
    html[data-theme="light"] .bg-blue-600.text-white,
    html[data-theme="light"] .bg-red-600 .text-white,
    html[data-theme="light"] .bg-blue-600 .text-white,
    html[data-theme="light"] button.bg-red-600,
    html[data-theme="light"] button.bg-blue-600,
    html[data-theme="light"] a.bg-red-600,
    html[data-theme="light"] a.bg-blue-600 {
        color: #ffffff !important;
    }

    html[data-theme="light"] .text-neutral-500,
    html[data-theme="light"] .text-neutral-400,
    html[data-theme="light"] .text-neutral-300,
    html[data-theme="light"] .text-slate-400,
    html[data-theme="light"] .text-zinc-400,
    html[data-theme="light"] .text-zinc-300,
    html[data-theme="light"] .text-gray-500,
    html[data-theme="light"] .dark\:text-gray-400 {
        color: #52525b !important;
    }

    html[data-theme="light"] .border-white\/5,
    html[data-theme="light"] .border-white\/10,
    html[data-theme="light"] .border-white\/15,
    html[data-theme="light"] .border-slate-800,
    html[data-theme="light"] .dark\:border-gray-700 {
        border-color: #d4d4d8 !important;
    }

    html[data-theme="light"] input,
    html[data-theme="light"] select,
    html[data-theme="light"] textarea {
        background-color: #ffffff !important;
        color: #18181b !important;
        border-color: #d4d4d8 !important;
    }

    html[data-theme="light"] input::placeholder,
    html[data-theme="light"] textarea::placeholder {
        color: #71717a !important;
    }

    html[data-theme="light"] .shadow-black\/40,
    html[data-theme="light"] .shadow-2xl {
        box-shadow: 0 20px 55px rgba(15, 23, 42, .12) !important;
    }

    html[data-theme="light"] section.relative.hidden.overflow-hidden,
    html[data-theme="light"] section.relative.hidden.overflow-hidden .text-white,
    html[data-theme="light"] section.relative.hidden.overflow-hidden .text-neutral-300,
    html[data-theme="light"] section.relative.hidden.overflow-hidden .text-neutral-400 {
        color: #ffffff !important;
    }

    html[data-theme="light"] header.sticky {
        background-color: rgba(255, 255, 255, .92) !important;
        color: #18181b !important;
    }
</style>

<script>
    window.toggleFitzoneTheme = function () {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', nextTheme);
        localStorage.setItem('fitzone-theme', nextTheme);

        document.querySelectorAll('[data-theme-label]').forEach((label) => {
            label.textContent = nextTheme === 'dark' ? 'Dark' : 'Light';
        });
    };

    window.addEventListener('DOMContentLoaded', function () {
        const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
        document.querySelectorAll('[data-theme-label]').forEach((label) => {
            label.textContent = currentTheme === 'dark' ? 'Dark' : 'Light';
        });
    });
</script>
