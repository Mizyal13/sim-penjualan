<?php
	
session_start();
if(!isset($_SESSION["login"])){
	header("Location: ../login.php");
	exit;
}

if (isset($_SESSION['level']) && strtolower($_SESSION['level']) === 'kasir') {
	$_SESSION['flash'] = [
		'title' => 'Akses Ditolak',
		'message' => 'Kasir tidak diizinkan mengubah data barang.',
		'type' => 'warning'
	];
	header("Location: ../index.php?p=barang");
	exit;
}

include "../system/proses.php";
	$idb = $_GET['idb'];
	$hapus = $db->delete("barang","id_brg='$idb'");
	if( $hapus ){
		$_SESSION['flash'] = [
			'title' => 'Data Barang',
			'message' => 'Data barang berhasil dihapus.',
			'type' => 'success'
		];
	}else{
		$_SESSION['flash'] = [
			'title' => 'Data Barang',
			'message' => 'Data barang gagal dihapus.',
			'type' => 'error'
		];
	}

	header("Location: ../index.php?p=barang");
	exit;
	
 ?>
