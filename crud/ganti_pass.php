<?php
	session_start();
	include '../system/proses.php';

	if(!isset($_POST['submit'])){
		header("Location: ../index.php?p=ganti_password");
		exit;
	}

	$id_petugas = $_POST['id_user'];
	$password_old = $_POST['password_old'];
	$password_new = $_POST['password_new'];
	$password_conf = $_POST['password_conf'];

	$result = $db->get("*","petugas","WHERE id_petugas='$id_petugas'");
	$data = $result->fetch();

	if(!$data || $password_old != $data['password']){
		$_SESSION['flash'] = [
			'title' => 'Ganti Password',
			'message' => 'Password lama tidak sesuai.',
			'type' => 'error'
		];
		header("Location: ../index.php?p=ganti_password");
		exit;
	}

	if(strlen($password_new) < 5){
		$_SESSION['flash'] = [
			'title' => 'Ganti Password',
			'message' => 'Minimal panjang password baru adalah 5 karakter.',
			'type' => 'warning'
		];
		header("Location: ../index.php?p=ganti_password");
		exit;
	}

	if($password_new != $password_conf){
		$_SESSION['flash'] = [
			'title' => 'Ganti Password',
			'message' => 'Konfirmasi password tidak sesuai.',
			'type' => 'error'
		];
		header("Location: ../index.php?p=ganti_password");
		exit;
	}

	$edit = $db->update("petugas","password='$password_new'","id_petugas='$id_petugas'");
	if($edit){
		$_SESSION['flash'] = [
			'title' => 'Ganti Password',
			'message' => 'Password berhasil diubah.',
			'type' => 'success'
		];
	}else{
		$_SESSION['flash'] = [
			'title' => 'Ganti Password',
			'message' => 'Password gagal diubah.',
			'type' => 'error'
		];
	}

	header("Location: ../index.php?p=ganti_password");
	exit;
?>
