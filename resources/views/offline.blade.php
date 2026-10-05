<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Offline - Tap Review Cards</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 50px 40px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            max-width: 420px;
            width: 100%;
        }
        .icon { font-size: 64px; margin-bottom: 20px; }
        h1 { color: #1e293b; font-size: 26px; margin-bottom: 12px; }
        p  { color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 28px; }
        button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }
        button:hover { background: #4338ca; }
        img { height: 40px; margin-bottom: 24px; }
    </style>
</head>
<body>
    <div class="container">
        <img src="images/logo.png" alt="Tap Review Cards">
        <div class="icon">📶</div>
        <h1>You're Offline</h1>
        <p>No internet connection. Please check your network and try again.</p>
        <button onclick="window.location.reload()">🔄 Try Again</button>
    </div>
</body>
</html>