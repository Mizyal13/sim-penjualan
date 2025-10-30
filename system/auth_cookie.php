<?php
/**
 * Helper untuk mengelola cookie autentikasi sederhana.
 */

const AUTH_COOKIE_NAME = 'simpenjualan_auth';
const AUTH_COOKIE_TTL = 18000; // 5 jam dalam detik
const AUTH_COOKIE_SECRET = 'sim-penjualan-auth-secret-2024';

function normalize_auth_level($level): string
{
    return strtolower(trim((string) $level));
}

/**
 * Membuat signature HMAC untuk payload cookie.
 */
function build_auth_signature(array $payload): string
{
	$sorted = $payload;
	ksort($sorted);
	return hash_hmac(
		'sha256',
		json_encode($sorted, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
		AUTH_COOKIE_SECRET
	);
}

/**
 * Mengembalikan informasi apakah koneksi saat ini memakai HTTPS.
 */
function is_secure_connection(): bool
{
	if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
		return true;
	}

	return (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] === '443');
}

/**
 * Menyetel cookie autentikasi.
 */
function set_auth_cookie(array $userData): void
{
	$issuedAt = time();
	$payload = [
		'id' => $userData['id'],
		'username' => $userData['username'],
		'level' => normalize_auth_level($userData['level'] ?? ''),
		'iat' => $issuedAt,
		'exp' => $issuedAt + AUTH_COOKIE_TTL,
	];

	$payload['sig'] = build_auth_signature($payload);
	$value = base64_encode(json_encode($payload));

	setcookie(
		AUTH_COOKIE_NAME,
		$value,
		$payload['exp'],
		'/',
		'',
		is_secure_connection(),
		true
	);
}

/**
 * Menghapus cookie autentikasi.
 */
function clear_auth_cookie(): void
{
	setcookie(
		AUTH_COOKIE_NAME,
		'',
		time() - 3600,
		'/',
		'',
		is_secure_connection(),
		true
	);
	unset($_COOKIE[AUTH_COOKIE_NAME]);
}

/**
 * Mengambil payload cookie autentikasi.
 *
 * @return array|null
 */
function get_auth_cookie_payload(): ?array
{
	if (empty($_COOKIE[AUTH_COOKIE_NAME])) {
		return null;
	}

	$decoded = base64_decode($_COOKIE[AUTH_COOKIE_NAME], true);
	if ($decoded === false) {
		return null;
	}

	$data = json_decode($decoded, true);
	if (
		!is_array($data) ||
		!isset($data['id'], $data['username'], $data['level'], $data['iat'], $data['exp'], $data['sig'])
	) {
		return null;
	}

	$sig = $data['sig'];
	unset($data['sig']);

	$expectedSig = build_auth_signature($data);
	if (!hash_equals($expectedSig, $sig)) {
		return null;
	}

	$data['level'] = normalize_auth_level($data['level']);
	return $data;
}

/**
 * Mengecek apakah payload telah kedaluwarsa.
 */
function auth_cookie_expired(array $payload): bool
{
	return (isset($payload['exp']) && $payload['exp'] < time());
}
