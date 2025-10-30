<?php
	
	
	session_start();
	if(!isset($_SESSION["login"])){
		header("Location: ../login.php");
		exit;
	}

include "../system/proses.php";

	if( isset($_POST['simpan_jenis']) ){
		$simpan=$db->insert("jenis","'$_POST[id_jenis]',
									'$_POST[nama_jenis]'");
		if( $simpan ){
			$_SESSION['flash'] = [
				'title' => 'Jenis Barang',
				'message' => 'Jenis barang berhasil disimpan.',
				'type' => 'success'
			];
		}else{
			$_SESSION['flash'] = [
				'title' => 'Jenis Barang',
				'message' => 'Jenis barang gagal disimpan.',
				'type' => 'error'
			];
		}
	}else{
		$edit=$db->update("jenis","id_jenis='$_POST[id_jenis]',
									nama_jenis='$_POST[nama_jenis]'","id_jenis = '$_POST[id_jenis]'");

		if( $edit ){
			$_SESSION['flash'] = [
				'title' => 'Jenis Barang',
				'message' => 'Perubahan jenis barang berhasil disimpan.',
				'type' => 'success'
			];
		}else{
			$_SESSION['flash'] = [
				'title' => 'Jenis Barang',
				'message' => 'Perubahan jenis barang gagal disimpan.',
				'type' => 'error'
			];
		}
	}

	header("Location: ../index.php?p=jenis_barang");
	exit;

 ?>
