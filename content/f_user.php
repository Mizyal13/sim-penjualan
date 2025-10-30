<?php 
// Mencari ID Otomatis
	include "system/proses.php";
	error_reporting(0);
	if( empty($_GET['id_user']) ){
		$connect = mysqli_connect("localhost", "root", "", "db_penjualan");
		$query = "SELECT max(id_petugas) as maxKode FROM petugas";
		$hasil = mysqli_query($connect, $query);
		$data = mysqli_fetch_array($hasil);
		$kodebarang = $data['maxKode'];
		$nourut = (int) substr($kodebarang, 2, 2);
		$nourut++;
		$char = "U";
		$kodebarang = $char . sprintf("%02s", $nourut);
		$sub = 'simpan_user';
	}else{
		$kodebarang = $_GET['id_user'];
		$sub = 'edit_user';
	}
	$qr = $db->get("*","petugas","WHERE id_petugas='$_GET[id_user]'");
	$row = $qr ? $qr->fetch(PDO::FETCH_ASSOC) : null;
	$username = $row ? $row['username'] : "";
	$password = $row ? $row['password'] : "";
	$currentLevel = $row ? strtolower($row['level']) : "";
 ?>



<div class="judul-content">
	<h1>Input User</h1>
</div>
<div class="isi-content">
	<form action="crud/simpan_user.php" method="POST">
		<table>
			<tr>
				<td><label for="id_pelanggan">ID User</label></td>
			</tr>
			<tr>
				<td><input type="text" name="id_user" class="text disable" autocomplete="off" id="di_pelanggan" value="<?= $kodebarang; ?>" readonly></td>
			</tr>


			<tr>
				<td><label for="username_input">Username</label></td>
			</tr>
			<tr>
				<td><input type="text" name="username" class="text" autocomplete="off" required="" id="username_input" value="<?= htmlspecialchars($username); ?>"></td>
			</tr>


			<tr>
				<td><label for="password_input">Password</label></td>
			</tr>
			<tr>
				<td><input type="text" name="password" class="text" autocomplete="off" required="" id="password_input" value="<?= htmlspecialchars($password); ?>"></td>
			</tr>

			<tr>
				<td><label>Level</label></td>
			</tr>
			<tr>
				<td>
					<select class="text" name="level">
						<option disabled <?= $currentLevel ? "" : "selected"; ?>>-- Pilih Level --</option>
						<option value="admin" <?= $currentLevel === "admin" ? "selected" : ""; ?>>Admin</option>
						<option value="manager" <?= $currentLevel === "manager" ? "selected" : ""; ?>>Manager</option>
						<option value="kasir" <?= $currentLevel === "kasir" ? "selected" : ""; ?>>Kasir</option>
						
					</select>
				</td>
			</tr>



			

			<tr>
				<td><input type="submit" name="<?= $sub; ?>" value="Simpan" class="simpan"></td>
			</tr>


		</table>
	</form>
</div>
<script type="text/javascript" src="../assets/js/validasi.js"></script>
