<?php 
	
	session_start();
	if(!isset($_SESSION["login"])){
		header("Location: ../login.php");
		exit;
	}
	
include "../system/proses.php";
	$idu = $_GET['idu'];
	$hapus = $db->delete("petugas","id_petugas='$idu'");
	if( $hapus ){
		$_SESSION['flash'] = [
			'title' => 'Manajemen User',
			'message' => 'User berhasil dihapus.',
			'type' => 'success'
		];
	}else{
		$_SESSION['flash'] = [
			'title' => 'Manajemen User',
			'message' => 'User gagal dihapus.',
			'type' => 'error'
		];
	}

	header("Location: ../index.php?p=user");
	exit;
 ?>
