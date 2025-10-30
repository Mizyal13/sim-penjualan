<?php
	
	session_start();
	if(!isset($_SESSION["login"])){
		header("Location: ../login.php");
		exit;
	}
	 
include "../system/proses.php";
	$idj = $_GET['idj'];
	$hapus = $db->delete("jenis","id_jenis=$idj");
	if( $hapus ){
		$_SESSION['flash'] = [
			'title' => 'Jenis Barang',
			'message' => 'Data jenis barang berhasil dihapus.',
			'type' => 'success'
		];
	}else{
		$_SESSION['flash'] = [
			'title' => 'Jenis Barang',
			'message' => 'Data jenis barang gagal dihapus.',
			'type' => 'error'
		];
	}

	header("Location: ../index.php?p=jenis_barang");
	exit;
	
 ?>
