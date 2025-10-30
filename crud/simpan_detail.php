<?php
	include "../system/proses.php";

	header('Content-Type: application/json');

	$idTransaksi = isset($_POST['id_transaksi']) ? trim($_POST['id_transaksi']) : '';
	$idBarang = isset($_POST['id_barang']) ? trim($_POST['id_barang']) : '';
	$jumlahBeli = isset($_POST['jumlah_beli']) ? (int) $_POST['jumlah_beli'] : 0;
	$subtotal = isset($_POST['total']) ? (int) $_POST['total'] : 0;

	if ($idTransaksi === '' || $idBarang === '' || $jumlahBeli <= 0 || $subtotal <= 0) {
		http_response_code(422);
		echo json_encode([
			'success' => false,
			'message' => 'Lengkapi data barang dan jumlah terlebih dahulu.'
		]);
		exit;
	}

	try {
		$stmt = $db->con->prepare("INSERT INTO detail_transaksi (id_transaksi, id_brg, jumlah_beli, subtotal) VALUES (:id_transaksi, :id_barang, :jumlah_beli, :subtotal)");
		$stmt->execute([
			':id_transaksi' => $idTransaksi,
			':id_barang' => $idBarang,
			':jumlah_beli' => $jumlahBeli,
			':subtotal' => $subtotal
		]);

		echo json_encode([
			'success' => true,
			'message' => 'Detail transaksi berhasil disimpan.'
		]);
	} catch (PDOException $e) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Detail transaksi gagal disimpan.',
			'error' => $e->getMessage()
		]);
	}
?>
