<?php
	
	
session_start();
if(!isset($_SESSION["login"])){
	header("Location: ../login.php");
	exit;
}

if (isset($_SESSION['level']) && strtolower($_SESSION['level']) === 'kasir') {
	$_SESSION['flash'] = [
		'title' => 'Akses Ditolak',
		'message' => 'Kasir tidak diizinkan mengubah data pelanggan.',
		'type' => 'warning'
	];
	header("Location: ../index.php?p=pelanggan");
	exit;
}
	 
include "../system/proses.php";
	$idp = $_GET['idp'];
	$hapus = $db->delete("pelanggan","id_pelanggan='$idp'");
	if( $hapus ){
		$_SESSION['flash'] = [
			'title' => 'Data Pelanggan',
			'message' => 'Data pelanggan berhasil dihapus.',
			'type' => 'success'
		];
	}else{
		$_SESSION['flash'] = [
			'title' => 'Data Pelanggan',
			'message' => 'Data pelanggan gagal dihapus.',
			'type' => 'error'
		];
	}

	header("Location: ../index.php?p=pelanggan");
	exit;
 ?>
