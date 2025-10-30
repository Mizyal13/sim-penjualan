<?php
	include "../system/proses.php";

	header('Content-Type: application/json');

	$idDetail = isset($_POST['id_detail_transaksi']) ? (int) $_POST['id_detail_transaksi'] : 0;

	if ($idDetail <= 0) {
		http_response_code(422);
		echo json_encode([
			'success' => false,
			'message' => 'ID detail transaksi tidak valid.'
		]);
		exit;
	}

	try {
		$hapus = $db->delete("detail_transaksi","id_detail_transaksi='$idDetail'");
		if ($hapus) {
			echo json_encode([
				'success' => true,
				'message' => 'Detail transaksi berhasil dihapus.'
			]);
		} else {
			http_response_code(500);
			echo json_encode([
				'success' => false,
				'message' => 'Detail transaksi gagal dihapus.'
			]);
		}
	} catch (PDOException $e) {
		http_response_code(500);
		echo json_encode([
			'success' => false,
			'message' => 'Detail transaksi gagal dihapus.',
			'error' => $e->getMessage()
		]);
	}
?>
