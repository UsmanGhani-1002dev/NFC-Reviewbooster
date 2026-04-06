<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Review Submitted</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    .confetti {
      position: absolute;
      width: 12px;
      height: 12px;
      border-radius: 50%;
      opacity: 0.9;
      z-index: 10;
      pointer-events: none;
    }

    @keyframes pop {
      0% {
        transform: translate(0, 0) scale(1);
        opacity: 1;
      }
      100% {
        transform: translate(var(--x), var(--y)) scale(0.8);
        opacity: 0;
      }
    }

    .popper {
      animation: pop 1.2s ease-out forwards;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-blue-100 to-purple-100 min-h-screen flex items-center justify-center relative overflow-visible">

  <!-- Confetti Layer -->
  <div id="confetti-container" class="fixed inset-0 overflow-visible z-10 pointer-events-none"></div>

  <!-- Success Card -->
  <div id="success-box" class="relative z-20 p-10 max-w-lg bg-white/80 backdrop-blur-md rounded-3xl shadow-2xl border border-white/40 text-center">
    <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center bg-emerald-500 rounded-full shadow-lg">
      <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <h1 class="text-4xl font-extrabold text-gray-800 mb-3">Thanks for your post!</h1>
    <p class="text-gray-600 mb-6">People like you make us more helpful.</p>

    @php
        $website = session('business_website');
    @endphp

    @if($website)
        <a href="{{ $website }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
          Visit our Website
        </a>
    @else
        <a href="{{ url('/') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
          Continue Browsing
        </a>
    @endif
  </div>

  <script>
    function popConfetti() {
      const container = document.getElementById("confetti-container");
      const box = document.getElementById("success-box");
      const rect = box.getBoundingClientRect();
      const centerX = rect.left + rect.width / 2;
      const centerY = rect.top + rect.height / 2;

      const colors = ['#EF4444', '#10B981', '#3B82F6', '#F59E0B', '#8B5CF6', '#EC4899'];
      const count = 70;

      for (let i = 0; i < count; i++) {
        const confetti = document.createElement("div");
        const color = colors[Math.floor(Math.random() * colors.length)];

        const angle = Math.random() * 2 * Math.PI;
        const radius = Math.random() * 500 + 200; // Wider spread (previously ~150)
        const x = Math.cos(angle) * radius;
        const y = Math.sin(angle) * radius;

        confetti.classList.add("confetti", "popper");
        confetti.style.backgroundColor = color;
        confetti.style.left = `${centerX}px`;
        confetti.style.top = `${centerY}px`;
        confetti.style.setProperty("--x", `${x}px`);
        confetti.style.setProperty("--y", `${y}px`);
        confetti.style.width = `${Math.random() * 10 + 6}px`;
        confetti.style.height = confetti.style.width;

        container.appendChild(confetti);

        setTimeout(() => confetti.remove(), 1500);
      }
    }

    window.onload = popConfetti;
  </script>
</body>
</html>
