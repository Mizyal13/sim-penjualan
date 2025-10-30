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

	if( isset($_POST['simpan_pelanggan']) ){
		$simpan=$db->insert("pelanggan","'$_POST[id_pelanggan]',
									'$_POST[nama_pelanggan]',
									'$_POST[alamat]',
									'$_POST[no_telp]',
									'$_POST[email]'");
		if( $simpan ){
			$_SESSION['flash'] = [
				'title' => 'Data Pelanggan',
				'message' => 'Pelanggan baru berhasil disimpan.',
				'type' => 'success'
			];
		}else{
			$_SESSION['flash'] = [
				'title' => 'Data Pelanggan',
				'message' => 'Pelanggan baru gagal disimpan.',
				'type' => 'error'
			];
		}
		
	}else{
		$edit=$db->update("pelanggan","id_pelanggan='$_POST[id_pelanggan]',
									nama='$_POST[nama_pelanggan]',
									alamat='$_POST[alamat]',
									no_telp='$_POST[no_telp]',
									email='$_POST[email]'",
									"id_pelanggan = '$_POST[id_pelanggan]'");

		if( $edit ){
			$_SESSION['flash'] = [
				'title' => 'Data Pelanggan',
				'message' => 'Perubahan data pelanggan berhasil disimpan.',
				'type' => 'success'
			];
		}else{
			$_SESSION['flash'] = [
				'title' => 'Data Pelanggan',
				'message' => 'Perubahan data pelanggan gagal disimpan.',
				'type' => 'error'
			];
		}
	}

	header("Location: ../index.php?p=pelanggan");
	exit;

 ?>
 
