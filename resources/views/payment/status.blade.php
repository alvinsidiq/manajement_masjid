<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title ?? 'Status Pembayaran' }}</title>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
  <style>
    body { font-family: 'Figtree', sans-serif; background: #f4f5f7; margin:0; padding:0; }
    .wrap { max-width: 540px; margin: 48px auto; background: #fff; border-radius: 16px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.06); }
    h1 { margin: 0 0 12px 0; font-size: 24px; color: #0f172a; }
    p { margin: 0; color: #475569; line-height: 1.5; }
    .actions { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
    a.button { display: inline-block; padding: 10px 16px; border-radius: 10px; text-decoration: none; background: #0ea5e9; color: #fff; font-weight: 600; }
    a.secondary { background: #e2e8f0; color: #0f172a; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>{{ $title ?? 'Status Pembayaran' }}</h1>
    <p>{{ $message ?? 'Silakan kembali ke aplikasi untuk melanjutkan.' }}</p>
    <div class="actions">
      <a class="button" href="{{ route('home') }}">Kembali ke Beranda</a>
      <a class="secondary button" href="javascript:window.close();">Tutup</a>
    </div>
  </div>
</body>
</html>
