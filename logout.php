<?php 
	session_start();
	require_once __DIR__ . "/system/auth_cookie.php";

	clear_auth_cookie();

	$_SESSION = [];
	$_SESSION['flash'] = [
		'title' => 'Logout Berhasil',
		'message' => 'Anda telah keluar dari SIM Penjualan.',
		'type' => 'info'
	];

	session_regenerate_id(true);

	header("Location: login.php");
	exit;
 ?>
