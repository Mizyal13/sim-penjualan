<?php
	include "../system/proses.php";

	$idBarang = isset($_POST['id_barang']) ? $_POST['id_barang'] : '';
	$query = $db->get("*", "barang", "WHERE id_brg = '$idBarang'");
	$tampil = $query ? $query->fetch(PDO::FETCH_ASSOC) : false;

	if (!$tampil) {
		echo json_encode([
			'id_barang' => '',
			'nama_barang' => '',
			'harga' => ''
		]);
		exit;
	}

	echo json_encode([
		'id_barang' => $tampil['id_brg'],
		'nama_barang' => $tampil['nama_brg'],
		'harga' => $tampil['harga']
	]);
?>
