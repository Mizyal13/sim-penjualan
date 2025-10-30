<?php 
	include "../system/proses.php";
	$id = $_GET['idt'];

	$items = [];
	$tot = 0;
	try{
		$qw = $db->get(
			"detail_transaksi.id_transaksi, detail_transaksi.id_detail_transaksi, barang.nama_brg, barang.harga, detail_transaksi.jumlah_beli, detail_transaksi.subtotal",
			"detail_transaksi",
			"INNER JOIN barang on detail_transaksi.id_brg = barang.id_brg WHERE detail_transaksi.id_transaksi = '$id' ORDER BY detail_transaksi.id_detail_transaksi ASC"
		);
		if($qw){
			$items = $qw->fetchAll(PDO::FETCH_ASSOC);
			foreach($items as $row){
				$tot += (int) $row['subtotal'];
			}
		}
	}catch (Exception $e){
		$items = [];
		$tot = 0;
	}
 ?>
<div class="pos-cart-list">
	<?php if(empty($items)): ?>
		<div class="pos-cart-empty">
			<i class="fas fa-shopping-basket"></i>
			<p>Keranjang masih kosong. Pilih produk untuk memulai transaksi.</p>
		</div>
	<?php else: ?>
		<?php foreach($items as $row): 
			$harga = number_format((int) $row['harga'], 0, ',', '.');
			$subtotal = number_format((int) $row['subtotal'], 0, ',', '.');
			?>
			<div class="pos-cart-item">
				<div class="pos-cart-item-main">
					<div class="pos-cart-item-header">
						<span class="pos-cart-item-name"><?= htmlspecialchars($row['nama_brg']); ?></span>
						<span class="pos-cart-item-subtotal">Rp <?= $subtotal; ?></span>
					</div>
					<div class="pos-cart-item-meta">
						<span class="pos-cart-item-code">#<?= htmlspecialchars($row['id_transaksi']); ?></span>
						<span class="pos-cart-item-qty"><?= (int) $row['jumlah_beli']; ?> x Rp <?= $harga; ?></span>
					</div>
				</div>
				<button type="button" class="pos-cart-remove" onclick="hapus_detail(<?= (int) $row['id_detail_transaksi']; ?>)" title="Hapus item">
					<i class="fas fa-times"></i>
				</button>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>

<script type="text/javascript">
	$('#subtotal').val("<?php echo $tot; ?>");

	// Mencari Diskon
	var pl = $('#kategori').val();
	var subtotal = parseFloat($('#subtotal').val()) || 0;
	var diskon = 0;
	if(pl === "Pelanggan"){
		diskon = subtotal * 0.05;
	}
	$('#diskon').val(diskon);

	// Mencari Total bayar
	var total = subtotal - diskon;
	$('#totalbayar').val(total);
</script>
		
