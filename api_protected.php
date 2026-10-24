<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$secretKey = 'ini-adalah-kunci-rahasia-super-panjang-dan-aman-banget-12345';

// 1. Ambil semua HTTP Headers
$headers = apache_request_headers();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($headers['authorization']) ? $headers['authorization'] : '');

// 2. Cek apakah header Authorization dengan format Bearer tersedia
if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Akses ditolak! Token JWT tidak ditemukan pada Header Authorization.'
    ]);
    exit;
}

$jwt = $matches[1];

try {
    // 3. Dekode dan verifikasi token menggunakan kunci rahasia & algoritma yang sama
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));

    // 4. Jika valid, berikan data rahasia
    echo json_encode([
        'status' => 'success',
        'message' => 'Autentikasi JWT Berhasil! Anda berhak mengakses data ini.',
        'user_logged_in' => $decoded->data,
        'token_expired_at' => date('Y-m-d H:i:s', $decoded->exp)
    ]);

} catch (Exception $e) {
    // Jika token palsu, diubah orang, atau sudah kedaluwarsa (expired)
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Token tidak valid atau sudah kedaluwarsa!',
        'error_detail' => $e->getMessage()
    ]);
}