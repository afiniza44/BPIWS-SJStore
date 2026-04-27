<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>403 — Akses Ditolak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="text-center">
        <div class="display-1 fw-bold text-danger">403</div>
        <h2 class="mb-3">Akses Ditolak</h2>
        <p class="text-muted">{{ $exception->getMessage() ?? 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}</p>
        <a href="/" class="btn btn-primary mt-2">Kembali ke Beranda</a>
    </div>
</body>
</html>
