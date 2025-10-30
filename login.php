<?php
	session_start();
	require_once __DIR__ . "/system/auth_cookie.php";

	$cookiePayload = get_auth_cookie_payload();
	if ($cookiePayload) {
		if (auth_cookie_expired($cookiePayload)) {
			clear_auth_cookie();
		} else {
		$_SESSION['login'] = $cookiePayload['id'];
		$_SESSION['login_id'] = $cookiePayload['id'];
		$_SESSION['username'] = $cookiePayload['username'];
		$_SESSION['level'] = normalize_auth_level($cookiePayload['level']);
			header("Location: index.php");
			exit;
		}
	}
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SIM Penjualan — Login</title>
	<link rel="stylesheet" type="text/css" href="assets/css/login-3d.css">
	<link rel="stylesheet" type="text/css" href="assets/css/toast.css">
	<link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
</head>
<body class="login-body">
	<main class="login-shell">
		<section class="login-panel tilt-card" data-float="26">
			<div class="panel-head">
				<span class="panel-kicker"><i class="fas fa-store"></i> SIM Penjualan</span>
				<h1>Masuk ke Dashboard</h1>
				<p>Kelola barang, pelanggan, dan transaksi dalam satu ruang kerja futuristik.</p>
			</div>
			<form class="login-form" action="system/cek_login.php" method="POST">
				<label class="field">
					<i class="field-icon fas fa-user"></i>
					<input type="text" name="username" placeholder="Username Anda" autocomplete="off" required>
				</label>
				<label class="field">
					<i class="field-icon fas fa-lock"></i>
					<input type="password" name="password" placeholder="Password" required>
				</label>
				<button type="submit" name="submit" class="login-button">
					<span>Masuk</span>
					<i class="fas fa-arrow-right"></i>
				</button>
			</form>
			<div class="panel-footer">
				<i class="fas fa-info-circle"></i>
				<span>Gunakan kredensial yang diberikan admin untuk mengakses sistem.</span>
			</div>
		</section>
		<aside class="login-showcase tilt-card" data-float="18">
			<div class="showcase-sphere"></div>
			<div class="showcase-header">
				<h2>Kelompok Rizky Ganteng</h2>
				<p>Tim kreatif yang memadukan teknologi dan gaya untuk pengalaman kasir masa depan.</p>
			</div>
			<ul class="feature-list">
				<li><i class="fas fa-chart-line"></i> Muhammad Rizky: otak strategis yang menjaga data selalu akurat.</li>
				<li><i class="fas fa-box-open"></i> Mizyal Jillauzi: arsitek sistem yang memastikan alur kerja licin tanpa hambatan.</li>
				<li><i class="fas fa-shield-alt"></i> Zoel Fikar: penjaga keamanan yang membuat autentikasi terasa elegan dan sigap.</li>
			</ul>
		</aside>
	</main>

	<script type="text/javascript" src="assets/js/vanilla-tilt.min.js"></script>
	<script type="text/javascript" src="assets/js/ui-3d.js"></script>
	<script type="text/javascript" src="assets/js/app-toast.js"></script>
	<?php
	$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
	if ($flash) {
		unset($_SESSION['flash']);
		$toastData = $flash;
		if (isset($toastData['print'])) {
			unset($toastData['print']);
		}
	?>
	<script>
	(function(){
		var toastPayload = <?php echo json_encode($toastData); ?>;
		if (!toastPayload) {
			return;
		}

		function triggerToast(){
			var payload = Object.assign({ duration: 5200, dismissible: true }, toastPayload);
			if (window.AppToast && typeof AppToast.show === 'function') {
				AppToast.show(payload);
				return;
			}
			setTimeout(triggerToast, 150);
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', function(){
				setTimeout(triggerToast, 120);
			}, { once: true });
		} else {
			setTimeout(triggerToast, 120);
		}
	})();
	</script>
	<?php } ?>
</body>
</html>
