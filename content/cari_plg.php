<?php
	include "../system/proses.php";

	$idPelanggan = isset($_POST['id_pelanggan']) ? $_POST['id_pelanggan'] : '';
	$query = $db->get("*", "pelanggan", "WHERE id_pelanggan = '$idPelanggan'");
	$tampil = $query ? $query->fetch(PDO::FETCH_ASSOC) : false;

	if (!$tampil) {
		echo json_encode([
			'id_pelanggan' => '',
			'nama_pelanggan' => ''
		]);
		exit;
	}

	echo json_encode([
		'id_pelanggan' => $tampil['id_pelanggan'],
		'nama_pelanggan' => $tampil['nama']
	]);
?>
