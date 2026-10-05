<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Printable Design — {{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            color: #202124;
            margin: 0;
            padding: 28px 16px 60px;
            background: #eef1f6;
        }

        /* ---- Toolbar (screen only) ---- */
        .toolbar {
            max-width: 1100px;
            margin: 0 auto 26px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
        }
        .toolbar h1 { font-size: 18px; font-weight: 800; margin: 0; flex: 1 1 auto; color: #0f172a; }
        .toolbar .meta { font-size: 12px; color: #64748b; font-weight: 600; width: 100%; margin-top: -6px; }
        .btn {
            border: none; cursor: pointer; font-family: inherit;
            font-size: 13px; font-weight: 700; padding: 10px 18px; border-radius: 10px;
            display: inline-flex; align-items: center; gap: 7px; text-decoration: none;
        }
        .btn svg { width: 16px; height: 16px; }
        .btn-print { background: #01A0FF; color: #fff; }
        .btn-back { background: #e2e8f0; color: #475569; }

        /* ---- Layout of design cards ---- */
        .sheet {
            max-width: 1100px; margin: 0 auto;
            display: flex; flex-wrap: wrap; gap: 34px; justify-content: center;
        }
        .design-block { display: flex; flex-direction: column; align-items: center; gap: 14px; }
        .design-caption { font-size: 12px; color: #475569; font-weight: 600; text-align: center; max-width: 300px; }
        .design-caption b { color: #0f172a; }
        .dl-btn { background: #059669; color: #fff; }
        .dl-btn:hover { background: #047857; }

        /* =====================  GOOGLE CARD  ===================== */
        .gcard {
            width: 340px; height: 540px;
            background: #ffffff; border-radius: 26px;
            box-shadow: 0 10px 34px rgba(15,23,42,.10);
            padding: 34px 30px; text-align: center;
            display: flex; flex-direction: column;
        }
        .gcard .biz {
            font-size: 22px; font-weight: 800; color: #202124;
            line-height: 1.15; letter-spacing: .2px;
            padding-bottom: 14px;
            overflow-wrap: anywhere;
            text-transform: uppercase;
        }
        .gcard .tap { font-size: 27px; font-weight: 500; color: #3c4043; line-height: 1.2; margin-top: 10px; }
        .gcard .logo-row {
            flex: 1 1 auto; display: flex; align-items: center; justify-content: center;margin-left:30px;
        }
        .gcard .logo-row .g { width: 160px; height: 160px; }
        .gcard .logo-row .waves { width: 44px; height: 70px; margin-bottom: 6px; }
        .gcard .review { font-size: 25px; font-weight: 500; color: #3c4043;line-height:38px }
        .gcard .review .word { display: block; font-size: 52px; font-weight: 700; letter-spacing: -1.5px; }
        .gcard .stars { display: flex; justify-content: center; gap: 2px; margin-top: 16px; }
        .gcard .stars svg { width: 30px; height: 30px; }
        .gcard .foot { margin-top: 16px; font-size: 15px; font-weight: 600; color: #3c4043; letter-spacing: .3px; }

        /* =====================  GOOGLE STAND  ===================== */
        /* Colour frame built as a 4-colour rounded border so BOTH the outer
           and inner corners round smoothly (no square notches). */
        .stand {
            position: relative; width: 530px; height: 690px;
            border-radius: 40px;
            background: #fff;
            box-shadow: 0 12px 40px rgba(15,23,42,.14);
        }
        .stand-inner {
            position: relative; height: 100%; background: #fff; border-radius: 24px;
            padding: 34px; text-align: center;
            display: flex; flex-direction: column; align-items: center;
        }

        /* ---- Black stand variant ---- */
        .stand.is-dark { background: #101012; }
        .stand.is-dark .stand-inner { background: #101012; }
        .stand.is-dark .biz,
        .stand.is-dark .review b,
        .stand.is-dark .scan { color: #ffffff; }
        .stand.is-dark .review,
        .stand.is-dark .foot { color: #e8eaed; }
        .stand.is-dark .divider { color: #6b6f76; }
        .stand.is-dark .divider .bar { background: #3a3d42; }
        .stand.is-dark .qr { background: transparent; padding: 0; width: 130px; height: 130px; }
        .stand .biz {
            font-size: 34px;
            font-weight: 800; 
            color: #202124; 
            line-height: 1.1;
            letter-spacing: .3px; 
            overflow-wrap: anywhere;
            text-transform: uppercase;
        }
        .stand .g { width: 150px; height: 150px; margin: 20px 0 15px; }
        .stand .review { font-size: 30px; font-weight: 500; color: #3c4043; line-height: 1.10; }
        .stand .review b { display: block; font-size: 52px; font-weight: 700; color: #202124; letter-spacing: -1px; }
        .stand .stars { margin: 5px 0 6px; display: flex; gap: 2px; }
        .stand .stars svg { width: 34px; height: 34px; }
        .stand .scan { font-size: 20px; font-weight: 700; color: #202124; letter-spacing: 2px; margin-top: 6px; }
        .stand .action-row {
            flex: 1 1 auto; display: flex; align-items: center; justify-content: center; gap: 22px; margin: 6px 0;
        }
        .stand .tap-icon { width: 130px; color: #EA4335; object-fit: contain; padding:15px; }
        .stand .divider { display: flex; flex-direction: column; align-items: center; color: #9aa0a6; font-weight: 600; }
        .stand .divider .bar { width: 1.5px; height: 34px; background: #dadce0; }
        .stand .divider span { padding: 6px 0; font-size: 16px; }
        .stand .qr { width: 130px; height: 130px; display: flex; align-items: center; justify-content: center; }
        .stand .qr img, .stand .qr canvas { width: 130px !important; height: 130px !important; }
        .stand .qr-missing {
            width: 130px; height: 130px; border: 2px dashed #dadce0; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; padding: 8px;
            font-size: 11px; color: #9aa0a6; font-weight: 600; text-align: center;
        }
        .stand .foot { font-size: 17px; font-weight: 600; color: #3c4043; letter-spacing: .3px; }

        /* During PNG export the card/stand paper fill is dropped so the
           downloaded image has a fully transparent background (only artwork,
           frame and text remain). On screen the fill stays for readability. */
        .exporting .gcard,
        .exporting .stand,
        .exporting .stand-inner { background: transparent !important; }

        .empty {
            max-width: 560px; margin: 40px auto; background: #fff; border-radius: 16px;
            padding: 40px; text-align: center; color: #475569; box-shadow: 0 8px 24px rgba(15,23,42,.08);
        }

        @media print {
            body { background: #fff; padding: 0; }
            .toolbar, .dl-btn { display: none !important; }
            .sheet { gap: 0; display: block; }
            .design-block { page-break-after: always; margin: 0 auto; padding: 12mm 0; }
            .design-caption { display: none; }
            .gcard, .stand { box-shadow: none; }
            @page { margin: 8mm; }
        }
    </style>
</head>
<body>

    @php
        // SVGs are wrapped as data-URI <img> so the browser rasterises them
        // natively — html2canvas mis-renders inline SVG (overlapping fills
        // merge, strokes vanish, sizes clip), but exports an <img> perfectly.
        $svgImg = fn ($class, $svg) => '<img class="' . $class . '" alt="" src="data:image/svg+xml;base64,' . base64_encode($svg) . '">';

        // Google "G" logo (official 4-colour mark).
        $gLogo = fn ($size) => $svgImg('g',
            '<svg xmlns="http://www.w3.org/2000/svg" width="' . $size . '" height="' . $size . '" viewBox="0 0 48 48">'
            . '<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>'
            . '<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>'
            . '<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>'
            . '<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>'
            . '</svg>');

        $star = '<svg width="34" height="34" viewBox="0 0 24 24" fill="#FBBC05" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>';

        // Multi-colour "Google" wordmark (brand letter colours) for the card.
        $googleWord = '<span class="word">'
            . '<span style="color:#4285F4">G</span>'
            . '<span style="color:#EA4335">o</span>'
            . '<span style="color:#FBBC05">o</span>'
            . '<span style="color:#4285F4">g</span>'
            . '<span style="color:#34A853">l</span>'
            . '<span style="color:#EA4335">e</span>'
            . '</span>';

        // Wireless / NFC signal waves shown next to the Google G on the card.
        $wifiWaves = $svgImg('waves',
            '<svg xmlns="http://www.w3.org/2000/svg" width="52" height="96" viewBox="0 0 52 96" fill="none" stroke="#3c4043" stroke-width="5" stroke-linecap="round">'
            . '<path d="M6 32 A 32 32 0 0 1 6 64"/>'
            . '<path d="M20 22 A 46 46 0 0 1 20 74"/>'
            . '<path d="M34 12 A 60 60 0 0 1 34 84"/>'
            . '</svg>');

        // "Tap your phone" icon for the stand. If a real graphic has been
        // dropped at public/images/tap-card.png it is used; otherwise a
        // built-in contactless icon is drawn.
        $tapIconSvg = $svgImg('tap-icon',
            '<svg xmlns="http://www.w3.org/2000/svg" width="140" height="120" viewBox="0 0 64 64" fill="none" stroke="#EA4335" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round">'
            . '<rect x="10" y="10" width="26" height="44" rx="5"/>'
            . '<line x1="10" y1="18" x2="36" y2="18"/><line x1="10" y1="46" x2="36" y2="46"/>'
            . '<path d="M45 22a14 14 0 0 1 0 20"/><path d="M51 17a22 22 0 0 1 0 30"/>'
            . '</svg>');
        $tapImg = public_path('images/tap-card.png');
        $tapHtml = file_exists($tapImg)
            ? '<img class="tap-icon" src="' . asset('images/tap-card.png') . '" alt="Tap your phone">'
            : $tapIconSvg;
    @endphp

    <div class="toolbar">
        <div style="flex:1 1 auto;">
            <h1>Printable Design — {{ $order->order_number }}</h1>
            <div class="meta">{{ count($designs) }} design(s)</div>
        </div>
        <button class="btn btn-print" onclick="window.print()">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print / Save as PDF
        </button>
        <a class="btn btn-back" href="{{ route('admin.orders.edit', $order) }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Order
        </a>
    </div>

    @if(empty($designs))
        <div class="empty">
            <h2 style="margin:0 0 8px;font-size:18px;color:#0f172a;">No card or stand items in this order</h2>
            <p style="margin:0;font-size:14px;">Printable designs are generated only for <b>Google Card</b> and <b>Google Stand</b> products.</p>
        </div>
    @else
    <div class="sheet">
        @foreach($designs as $i => $d)
            @php
                $bizName = $d['business'];
                $downloadSlug = \Illuminate\Support\Str::slug($order->order_number . '-' . $d['type'] . '-' . $bizName) ?: 'design';
            @endphp

            <div class="design-block">
                <div class="design-node" id="design-{{ $i }}">
                    @if($d['type'] === 'card')
                        {{-- ============ GOOGLE CARD ============ --}}
                        <div class="gcard">
                            <div class="biz">{{ $bizName }}</div>
                            <div class="tap">Tap your phone here</div>
                            <div class="logo-row">{!! $gLogo(158) !!}{!! $wifiWaves !!}</div>
                            <div class="review">Review us on{!! $googleWord !!}</div>
                            <div class="stars">{!! str_repeat($star, 5) !!}</div>
                            <div class="foot">tapreviewcards.co.uk</div>
                        </div>
                    @else
                        {{-- ============ GOOGLE STAND ============ --}}
                        <div class="stand {{ !empty($d['dark']) ? 'is-dark' : '' }}">
                            <div class="stand-inner">
                                <div class="biz">{{ $bizName }}</div>
                                {!! $gLogo(150) !!}
                                <div class="review">Review us on<b>Google</b></div>
                                <div class="stars">{!! str_repeat($star, 5) !!}</div>
                                <div class="scan">SCAN OR TAP</div>
                                <div class="action-row">
                                    {!! $tapHtml !!}
                                    <div class="divider"><div class="bar"></div><span>or</span><div class="bar"></div></div>
                                    @if($d['review_url'])
                                        <div class="qr" data-qr-url="{{ $d['review_url'] }}"@if(!empty($d['dark'])) data-qr-dark="1"@endif></div>
                                    @else
                                        <div class="qr-missing">Add a review link to this order to show a QR code</div>
                                    @endif
                                </div>
                                <div class="foot">TapReviewCards.co.uk</div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="design-caption">
                    <b>{{ $d['type'] === 'card' ? 'Google Card' : 'Google Stand' }}</b> · {{ $d['variant'] }} · Qty {{ $d['qty'] }}<br>
                    Business: <b>{{ $bizName }}</b>
                </div>

                <button class="btn dl-btn" onclick="downloadDesign('design-{{ $i }}', '{{ $downloadSlug }}')">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download PNG
                </button>
            </div>
        @endforeach
    </div>
    @endif

    <script>
        // Render QR codes for stand designs. Background is kept transparent so
        // the exported PNG stays transparent; a dark stand uses white (inverted)
        // modules, a light stand uses black modules.
        document.querySelectorAll('[data-qr-url]').forEach(function (el) {
            if (!el.dataset.qrUrl || el.dataset.qrRendered) return;
            var isDark = el.dataset.qrDark === '1';
            try {
                new QRCode(el, {
                    text: el.dataset.qrUrl,
                    width: 260,
                    height: 260,
                    colorDark: isDark ? '#ffffff' : '#000000',
                    colorLight: 'rgba(0,0,0,0)',
                    correctLevel: QRCode.CorrectLevel.M
                });
                el.dataset.qrRendered = '1';
            } catch (e) { console.error('QR render failed', e); }
        });

        async function downloadDesign(nodeId, slug) {
            var node = document.getElementById(nodeId);
            if (!node) return;
            node.classList.add('exporting'); // drop paper fill -> transparent PNG
            try {
                if (document.fonts && document.fonts.ready) { await document.fonts.ready; }
                var canvas = await html2canvas(node, {
                    scale: 2,
                    backgroundColor: null,
                    useCORS: true,
                    logging: false,
                    windowWidth: node.scrollWidth,
                    windowHeight: node.scrollHeight
                });
                var a = document.createElement('a');
                a.href = canvas.toDataURL('image/png');
                a.download = slug + '.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            } catch (e) {
                console.error(e);
                alert('Could not export the image. Please use "Print / Save as PDF" instead.');
            } finally {
                node.classList.remove('exporting');
            }
        }
    </script>
</body>
</html>
