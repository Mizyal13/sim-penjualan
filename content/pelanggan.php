<?php 
	include "system/proses.php";

	$role = isset($_SESSION['level']) ? strtolower($_SESSION['level']) : '';
	$isKasir = ($role === 'kasir');
 ?>


<div class="judul-content">
	<h1>Pelanggan</h1>
</div>
<div class="isi-content">
	<?php if (!$isKasir) { ?>
	<a href="index.php?p=f_pelanggan" class="link-tambah-barang">Tambah</a>
	<?php } ?>
	<div class="judul-home">
		<div class="divtabel">
		<table class="tabel98">
			<tr>
				<th>ID Pelanggan</th>
				<th>Nama Pelanggan</th>
				<th>alamat</th>
				<th>No.Telp</th>
				<th>Email</th>
				<?php if (!$isKasir) { ?>
				<th>Action</th>
				<?php } ?>
			</tr>
			<?php 
				$qw=$db->get("*","pelanggan","ORDER BY id_pelanggan ASC");
				foreach($qw as $tamp_pelanggan) {


			 ?>
			<tr>
				<td><?= $tamp_pelanggan['id_pelanggan']; ?></td>
				<td><?= $tamp_pelanggan['nama']; ?></td>
				<td><?= $tamp_pelanggan['alamat']; ?></td>
				<td><?= $tamp_pelanggan['no_telp']; ?></td>
				<td><?= $tamp_pelanggan['email']; ?></td>
				<?php if (!$isKasir) { ?>
				<td>
					<a href="crud/hapus_pelanggan.php?idp=<?= $tamp_pelanggan['id_pelanggan']; ?>" class="btn btn-merah" onclick="return confirm('Yakin Ingin Menghapus Data ?')"><i class="fa fa-trash-alt"></i> Hapus</a>
					<a href="index.php?p=f_pelanggan&id_pelanggan=<?= $tamp_pelanggan['id_pelanggan']; ?>" class="btn btn-kuning"><i class="fa fa-pen"></i> Edit</a>
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
