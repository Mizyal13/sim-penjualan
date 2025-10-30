<?php 
	$id_petugas = $_SESSION['login_id'];
	$username = $_SESSION['username'];
?>
<div class="judul-content">
	<h1>Ganti Password</h1>
</div>
<div class="isi-content">
	<form action="crud/ganti_pass.php" method="POST">
		<table>
			<tr>
				<td><label for="id_petugas">ID Petugas</label></td>
			</tr>
			<tr>
				<td><input type="text" name="id_user" class="text disable" autocomplete="off" id="id_petugas" value="<?php echo $id_petugas; ?>" readonly></td>
			</tr>


			<tr>
				<td><label for="username">Username</label></td>
			</tr>
			<tr>
				<td><input type="text" name="username" class="text disable" autocomplete="off" required="" id="username" value="<?php echo $username; ?>" readonly></td>
			</tr>


			<tr>
				<td><label for="old">Password Lama</label></td>
			</tr>
			<tr>
				<td>
					<div class="password-wrapper">
						<input type="password" name="password_old" class="text" autocomplete="off" required="" id="old">
						<button type="button" class="password-toggle" data-target="old" aria-label="Tampilkan password lama">
							<i class="fas fa-eye"></i>
						</button>
					</div>
				</td>
			</tr>

			<tr>
				<td><label for="new">Password Baru</label></td>
			</tr>
			<tr>
				<td>
					<div class="password-wrapper">
						<input type="password" name="password_new" class="text" autocomplete="off" required="" id="new">
						<button type="button" class="password-toggle" data-target="new" aria-label="Tampilkan password baru">
							<i class="fas fa-eye"></i>
						</button>
					</div>
				</td>
			</tr>

			<tr>
				<td><label for="conf">Konfirmasi Password</label></td>
			</tr>
			<tr>
				<td>
					<div class="password-wrapper">
						<input type="password" name="password_conf" class="text" autocomplete="off" required="" id="conf">
						<button type="button" class="password-toggle" data-target="conf" aria-label="Tampilkan konfirmasi password">
							<i class="fas fa-eye"></i>
						</button>
					</div>
				</td>
			</tr>

			



			

			<tr>
				<td><input type="submit" name="submit" value="Simpan" class="simpan"></td>
			</tr>


		</table>
	</form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
	var buttons = document.querySelectorAll('.password-toggle');
	if (!buttons.length) {
		return;
	}

	buttons.forEach(function (button) {
		button.addEventListener('click', function () {
			var targetId = button.getAttribute('data-target');
			var input = document.getElementById(targetId);
			if (!input) {
				return;
			}

			var isHidden = input.type === 'password';
			input.type = isHidden ? 'text' : 'password';
			button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
			button.classList.toggle('is-active', isHidden);

			var icon = button.querySelector('i');
			if (icon) {
				icon.classList.toggle('fa-eye');
				icon.classList.toggle('fa-eye-slash');
			}
		});
	});
});
</script>
