<?php 
	include "system/proses.php";

	$role = isset($_SESSION['level']) ? strtolower($_SESSION['level']) : '';
	$isKasir = ($role === 'kasir');
 ?>


<div class="judul-content">
	<h1>Barang</h1>
</div>
<div class="isi-content">
	<?php if (!$isKasir) { ?>
	<a href="index.php?p=f_barang" class="link-tambah-barang">Tambah</a>
	<?php } ?>
	<div class="judul-home">
		<div class="divtabel">
		<table class="tabel98">
			<tr>
				<th>ID Barang</th>
				<th>Nama Barang</th>
				<th>Harga</th>
				<th>Stok</th>
				<th>Tgl. Expired</th>
				<th>Jenis Barang</th>
				<?php if (!$isKasir) { ?>
				<th>Action</th>
				<?php } ?>
			</tr>
			<?php 
				$qw=$db->get("barang.id_brg, barang.nama_brg, jenis.nama_jenis, barang.harga, barang.stok, barang.expired","barang","INNER JOIN jenis on barang.id_jenis_brg = jenis.id_jenis ORDER BY barang.id_brg ASC");
				foreach($qw as $tamp_barang){


			 ?>
			<tr>
				<td><?= $tamp_barang['id_brg']; ?></td>
				<td><?= $tamp_barang['nama_brg']; ?></td>	
				<td><?= $tamp_barang['harga']; ?></td>
				<td><?= $tamp_barang['stok']; ?></td>
				<td><?= $tamp_barang['expired']; ?></td>
				<td><?= $tamp_barang['nama_jenis']; ?></td>
				<?php if (!$isKasir) { ?>
				<td>
					<a href="crud/hapus_barang.php?idb=<?= $tamp_barang['id_brg']; ?>" class="btn btn-merah" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="fa fa-trash-alt"></i> Hapus</a>

					
					<a href="index.php?p=f_barang&id_barang=<?php echo $tamp_barang['id_brg']; ?>" class="btn btn-kuning"><i class="fa fa-pen"></i> Edit</a>
				</td>
				<?php } ?>
			</tr>
			<?php 
				}
			 ?>
		</table>
		</div>
	</div>
</div>
