<?php
	session_start();
	include "../system/proses.php";

	$idp = isset($_POST['idplgnn']) ? trim($_POST['idplgnn']) : '';
	$ip = $idp === '' ? "Non Pelanggan" : $idp;

	if(!isset($_POST['simpan'])){
		header("Location: ../index.php?p=transaksi");
		exit;
	}

	$simpan = $db->insert("transaksi", "'$_POST[id_transaksi]',
									'$ip',
									'$_POST[tanggal]',
									'$_POST[id_user]',
									'$_POST[totalbayar]',
									'$_POST[diskon]',
									'$_POST[bayar]'");

	if($simpan){
		$_SESSION['flash'] = [
			'title' => 'Transaksi',
			'message' => 'Transaksi berhasil diproses.',
			'type' => 'success',
			'print' => $_POST['id_transaksi']
		];
	}else{
		$_SESSION['flash'] = [
			'title' => 'Transaksi',
			'message' => 'Transaksi gagal diproses.',
			'type' => 'error'
		];
	}

	header("Location: ../index.php?p=transaksi");
	exit;
?>
