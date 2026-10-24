<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;

// Ubah key menjadi minimal 32 karakter
$secretKey = 'ini-adalah-kunci-rahasia-super-panjang-dan-aman-banget-12345';

$message = '';
$tokenResult = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === 'admin' && $password === '123456') {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600; 
        
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => [
                'user_id' => 99,
                'username' => $username,
                'role' => 'Administrator'
            ]
        ];

        $jwt = JWT::encode($payload, $secretKey, 'HS256');
        
        $tokenResult = $jwt;
        $message = "Login Berhasil! Token JWT berhasil dibuat.";
    } else {
        $message = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Contoh Login JWT</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-xl font-bold text-slate-800 mb-4">Form Login (JWT Generator)</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-sm">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Username (isi: admin)</label>
                <input type="text" name="username" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Password (isi: 123456)</label>
                <input type="password" name="password" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                Login & Generate Token
            </button>
        </form>

        <?php if (!empty($tokenResult)): ?>
            <div class="mt-6">
                <label class="block text-xs font-semibold text-slate-600 mb-1">JWT Token Anda (Salin ini untuk tes API):</label>
                <textarea readonly class="w-full h-24 p-2 text-xs bg-slate-50 border border-slate-300 rounded-lg font-mono"><?= $tokenResult ?></textarea>
                <a href="api_protected.php" target="_blank" class="block text-center mt-3 text-sm text-blue-600 font-medium hover:underline">
                    Uji Akses ke Halaman Dilindungi &rarr;
                </a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>