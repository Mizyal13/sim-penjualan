<?php
	include '../system/proses.php';

	$tgl_awal = isset($_GET['tgl_awal']) ? trim($_GET['tgl_awal']) : '';
	$tgl_akhir = isset($_GET['tgl_akhir']) ? trim($_GET['tgl_akhir']) : '';

	if ($tgl_awal !== '' && $tgl_akhir === '') {
		$tgl_akhir = $tgl_awal;
	} elseif ($tgl_akhir !== '' && $tgl_awal === '') {
		$tgl_awal = $tgl_akhir;
	}

	$conditions = [];
	$params = [];

	if ($tgl_awal !== '') {
		$conditions[] = "transaksi.tanggal >= :tgl_awal";
		$params[':tgl_awal'] = $tgl_awal;
	}

	if ($tgl_akhir !== '') {
		$conditions[] = "transaksi.tanggal <= :tgl_akhir";
		$params[':tgl_akhir'] = $tgl_akhir;
	}

	$whereClause = "";
	if (!empty($conditions)) {
		$whereClause = "WHERE " . implode(" AND ", $conditions);
	}

	$sql = "SELECT transaksi.id_transaksi, transaksi.tanggal, petugas.username, transaksi.total, transaksi.diskon
			FROM transaksi
			INNER JOIN petugas ON transaksi.id_petugas = petugas.id_petugas
			{$whereClause}
			ORDER BY transaksi.tanggal ASC, transaksi.id_transaksi ASC";

	$stmt = $db->con->prepare($sql);
	$stmt->execute($params);
	$dataTransaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Print Laporan Per Tanggal</title>
	<link rel="stylesheet" type="text/css" href="../assets/css/custom.css">
</head>
<body style="background-color: #fff;">
	<div class="judul-content">
	<h1 style="text-align: center; font-family: 'Segoe UI', sans-serif; margin-top: 15px;">Laporan Per Tanggal</h1>
	<?php if ($tgl_awal !== '' || $tgl_akhir !== ''): ?>
		<p style="text-align:center; font-family:'Segoe UI', sans-serif; color:#475569; margin-top:6px;">
			Periode: 
			<strong><?php echo $tgl_awal !== '' ? $tgl_awal : '&mdash;'; ?></strong>
			s.d
			<strong><?php echo $tgl_akhir !== '' ? $tgl_akhir : '&mdash;'; ?></strong>
		</p>
	<?php endif; ?>
</div>
<div class="isi-content">
	<div class="judul-home">
		<div class="divtabel">
			
				<table class="tabel98">
					<tr>
						<th>ID Transaksi</th>
						<th>Tanggal</th>
						<th>Nama User</th>
						<th>Total</th>
						<th>Diskon</th>
						<th>Total Bayar</th>
						
					</tr>
					<?php 
						if (!empty($dataTransaksi)) {
							foreach($dataTransaksi as $tampil){
								$totbay = $tampil['total']-$tampil['diskon'];
					 ?>
						<tr>
							<td><?php echo $tampil['id_transaksi']; ?></td>
							<td><?php echo $tampil['tanggal']; ?></td>	
							<td><?php echo $tampil['username']; ?></td>
							<td><?php echo $tampil['total']; ?></td>
							<td><?php echo $tampil['diskon']; ?></td>
							<td><?php echo $totbay; ?></td>
							
						</tr>
					<?php 
							}
						} else {
					?>
						<tr>
							<td colspan="6" style="text-align:center; padding:22px 0; color:#64748b;">Tidak ada data transaksi pada rentang tanggal yang dipilih.</td>
						</tr>
					<?php } ?>
					
				</table>
			
		</div>
	</div>
</div>
</body>
</html>
<script type="text/javascript">
	window.print();
</script>
