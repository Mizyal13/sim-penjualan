<?php 
	
	
	session_start();
	if(!isset($_SESSION["login"])){
		header("Location: ../login.php");
		exit;
	}

	include "../system/proses.php";
	if (isset($_POST['simpan_user'])){
		$simpan = $db->insert("petugas","'$_POST[id_user]',
										'$_POST[username]',
										'$_POST[password]',
										'$_POST[level]'");
		if ($simpan){
			$_SESSION['flash'] = [
				'title' => 'Manajemen User',
				'message' => 'User baru berhasil disimpan.',
				'type' => 'success'
			];
		}else{
			$_SESSION['flash'] = [
				'title' => 'Manajemen User',
				'message' => 'User baru gagal disimpan.',
				'type' => 'error'
			];
		}
	}else{
		$edit=$db->update("petugas","id_petugas='$_POST[id_user]',
									username='$_POST[username]',password='$_POST[password]',level='$_POST[level]'","id_petugas = '$_POST[id_user]'");

		if( $edit ){
			$_SESSION['flash'] = [
				'title' => 'Manajemen User',
				'message' => 'Perubahan user berhasil disimpan.',
				'type' => 'success'
			];
		}else{
			$_SESSION['flash'] = [
				'title' => 'Manajemen User',
				'message' => 'Perubahan user gagal disimpan.',
				'type' => 'error'
			];
		}
	}
	header("Location: ../index.php?p=user");
	exit;
 ?>
