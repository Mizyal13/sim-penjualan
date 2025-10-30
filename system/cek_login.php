<?php 
	session_start();
	require "proses.php";
	require_once __DIR__ . "/auth_cookie.php";
	if(isset($_POST['submit'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$result = $db->get("*","petugas","WHERE username='$username' AND password='$password'");
		$row = $result->rowCount();
		$data = $result->fetch();
		if( $row > 0){
		$_SESSION['login']=$data['id_petugas'];
		$_SESSION['login_id'] = $data['id_petugas'];
		$_SESSION['username'] = $data['username'];
		$_SESSION['level'] = normalize_auth_level($data['level']);
			set_auth_cookie([
				'id' => $data['id_petugas'],
				'username' => $data['username'],
				'level' => $data['level'],
			]);
			$_SESSION['flash'] = [
				'title' => 'Login Berhasil',
				'message' => 'Halo, ' . $data['username'] . '! Selamat datang kembali di SIM Penjualan.',
				'type' => 'success',
				'duration' => 5200
			];
			header("Location: ../index.php");
			exit;
		}else{
			$_SESSION['flash'] = [
				'title' => 'Login Gagal',
				'message' => 'Username atau password salah.',
				'type' => 'error'
			];
		    header("Location: ../login.php");
			exit;
		}
	}
 ?>
 
