<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/tap fevicon.png') }}" type="image/x-icon">
    <meta name="robots" content="noindex, follow">

    @laravelPWA

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>

<body class="min-h-screen flex flex-col bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/bg.jpeg') }}');">

    <main class="flex-grow flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>

    <footer class="text-center py-4 bg-black/80 text-white text-sm flex-shrink-0">
        &copy; 2025 <span class="font-semibold">Review Boost</span>
    </footer>

    <script>
        window.deferredPrompt = null;

        window.addEventListener('beforeinstallprompt', (e) => {
            window.deferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-installable'));
        });

        window.addEventListener('appinstalled', () => {
            window.deferredPrompt = null;
            window.dispatchEvent(new CustomEvent('pwa-installed'));
        });

        window.installPWA = function() {
            if (window.deferredPrompt) {
                window.deferredPrompt.prompt();
                window.deferredPrompt.userChoice.then(() => {
                    window.deferredPrompt = null;
                    window.dispatchEvent(new CustomEvent('pwa-installed'));
                });
            } else {
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                if (isIOS) {
                    window.dispatchEvent(new CustomEvent('open-ios-install-modal'));
                } else {
                    alert('To install this app, tap your browser menu and select "Install App" or "Add to Home screen".');
                }
            }
        };
    </script>

    <!-- iOS PWA Install Modal -->
    <x-ios-pwa-modal />

</body>
</html>