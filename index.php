<?php

session_start();
require_once __DIR__ . "/system/auth_cookie.php";

error_reporting(0);

$cookiePayload = get_auth_cookie_payload();
if (!$cookiePayload) {
	$_SESSION = [];
	$_SESSION['flash'] = [
		'title' => 'Perlu Login',
		'message' => 'Sesi login tidak ditemukan. Silakan masuk kembali.',
		'type' => 'warning'
	];
	session_regenerate_id(true);
	header("Location: login.php");
	exit;
}

if (auth_cookie_expired($cookiePayload)) {
	clear_auth_cookie();
	$_SESSION = [];
	$_SESSION['flash'] = [
		'title' => 'Sesi Berakhir',
		'message' => 'Login Anda telah kedaluwarsa. Silakan masuk ulang.',
		'type' => 'warning'
	];
	session_regenerate_id(true);
	header("Location: login.php");
	exit;
}

$_SESSION['login'] = $cookiePayload['id'];
$_SESSION['login_id'] = $cookiePayload['id'];
$_SESSION['username'] = $cookiePayload['username'];
$_SESSION['level'] = $cookiePayload['level'];

$role = normalize_auth_level($_SESSION['level'] ?? '');
$level = $_SESSION['level'];

?>
<!DOCTYPE html>
<html>

<head>
	<title>SIM Penjualan</title>
	<!-- Style -->
	<link rel="stylesheet" type="text/css" href="assets/css/custom.css">
	<link rel="stylesheet" type="text/css" href="assets/css/toast.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" type="text/css" href="assets/fontawesome/css/all.min.css"
		integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

</head>

<body>
   <div class="header">
       <div class="nav-left">
           <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar" aria-expanded="true">
               <span class="bar"></span>
               <span class="bar"></span>
               <span class="bar"></span>
           </button>
           <div class="judul">
               <h1>SIM Penjualan</h1>
           </div>
       </div>

       <div class="nav-right"></div>

   </div>
	<div class="sidebar">

		<div class="menu">

			<ul>
		<?php
			switch ($role) {
				case "admin":
					$home = "";
					$user = "";
					$barang = "";
					$pelanggan = "";
					$jenis = "";
					$transaksi = "";
					$laporan = "hidden";
					$gp = "hidden";
					$lpr = "hidden";
					break;
				case "kasir":
					$home = "";
					$user = "hidden";
					$barang = "hidden";
					$pelanggan = "hidden";
					$jenis = "hidden";
					$transaksi = "";
					$laporan = "hidden";
					$gp = "";
					$lpr = "hidden";
					break;
				case "manager":
					$home = "";
					$user = "hidden";
					$barang = "hidden";
					$pelanggan = "hidden";
					$jenis = "hidden";
					$transaksi = "hidden";
					$laporan = "";
					$gp = "hidden";
					$lpr = "";
					break;
				default:
					$home = "";
					$user = "";
					$barang = "";
					$pelanggan = "";
					$jenis = "";
					$transaksi = "";
					$laporan = "hidden";
					$gp = "hidden";
					$lpr = "hidden";
					break;
			}
			?>
		<li <?php echo $home; ?>><a class="menu-link" href="index.php?p=home"><i class="fas fa-tachometer-alt"></i><span class="menu-text">Beranda</span></a>
		</li>

		<li <?php echo $user; ?>><a class="menu-link" href="index.php?p=user"><i class="fa fa-user"></i><span class="menu-text">User</span></a></li>

		<li <?php echo $barang; ?>><a class="menu-link" href="index.php?p=barang"><i class="fa fa-shopping-cart"></i><span class="menu-text">Barang</span></a>
		</li>

		<li <?php echo $pelanggan; ?>><a class="menu-link" href="index.php?p=pelanggan"><i class="fa fa-user-friends"></i><span class="menu-text">Pelanggan</span></a></li>

		<li <?php echo $jenis; ?>><a class="menu-link" href="index.php?p=jenis_barang"><i class="fa fa-tags"></i><span class="menu-text">Jenis Barang</span></a>
		</li>

		<li <?php echo $transaksi; ?>><a class="menu-link" href="index.php?p=transaksi"><i class="fas fa-table"></i><span class="menu-text">Transaksi</span></a>
		</li>

		<li <?php echo $gp; ?>><a class="menu-link" href="index.php?p=ganti_password"><i class="fas fa-user-lock"></i><span class="menu-text">Ganti Password</span></a></li>


		<li <?php echo $laporan; ?> id="lpr"><a class="menu-link" href="#"><i class="fas fa-print"></i><span class="menu-text">Laporan</span></a></li>


		<li <?php echo $lpr; ?> id="lpr1" class="submenu"><a
					href="index.php?p=laporan_pertanggal"><span>Per Tanggal</span></a></li>

		<li <?php echo $lpr; ?> id="lpr2" class="submenu"><a
					href="index.php?p=laporan_perbulan"><span>Per Bulan</span></a></li>

		<li <?php echo $lpr; ?> id="lpr3" class="submenu"><a
					href="index.php?p=laporan_pertahun"><span>Per Tahun</span></a></li>


		<li <?php echo $home; ?>><a class="menu-link" href="logout.php" data-confirm="Yakin ingin logout?" data-confirm-title="Konfirmasi Logout" data-confirm-yes="Keluar" data-confirm-type="warning"><i
					class="fa fa-sign-out-alt"></i><span class="menu-text">Logout</span></a></li>

			</ul>
		</div>
	</div>
	<div class="content">
		<?php
		if (empty($_GET['p'])) {
			echo "<script>document.location.href='index.php?p=home'</script>";
		} else {
			$p = $_GET['p'];
			include "content/$p.php";
		}
		?>
	</div>
	<script type="text/javascript" src="assets/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="assets/js/app-toast.js"></script>
	<script type="text/javascript" src="assets/js/vanilla-tilt.min.js"></script>
	<script type="text/javascript" src="assets/js/ui-3d.js"></script>
	<script type="text/javascript" src="assets/js/transaksi.js"></script>
	<script>
	(function(){
		var STORAGE_KEY = 'sim-penjualan-sidebar';
		var toggle = document.getElementById('sidebarToggle');
		var prefersCompact = localStorage.getItem(STORAGE_KEY);

		function applyState(compact){
			document.body.classList.toggle('compact', compact);
			if(toggle){
				toggle.setAttribute('aria-expanded', (!compact).toString());
			}
		}

		function responsiveState(){
			if (prefersCompact === null){
				applyState(window.innerWidth < 1100);
			}
		}

		if (prefersCompact !== null){
			applyState(prefersCompact === '1');
		}else{
			responsiveState();
		}

		if (toggle){
			toggle.addEventListener('click', function(){
				var newState = !document.body.classList.contains('compact');
				applyState(newState);
				localStorage.setItem(STORAGE_KEY, newState ? '1' : '0');
				prefersCompact = newState ? '1' : '0';
			});
		}

		window.addEventListener('resize', function(){
			if (prefersCompact === null){
				responsiveState();
			}
		});

		document.addEventListener('DOMContentLoaded', function(){
			if (document.querySelector('[data-pos-page="transaksi"]') && typeof buka_tab === 'function') {
				buka_tab();
			}
		});
	})();
	</script>
	<script>
	(function(){
		var backdrop, modal, titleEl, messageEl, confirmBtn, cancelBtn, iconEl;
		var activeTrigger = null;

		var ICON_MAP = {
			warning: '<i class="fas fa-exclamation-triangle"></i>',
			danger: '<i class="fas fa-exclamation-circle"></i>',
			info: '<i class="fas fa-info-circle"></i>',
			success: '<i class="fas fa-check"></i>'
		};

		function ensureModal(){
			if (backdrop) {
				return backdrop;
			}

			backdrop = document.createElement('div');
			backdrop.className = 'app-confirm-backdrop';
			backdrop.innerHTML = '' +
				'<div class="app-confirm">' +
					'<div class="app-confirm__icon"></div>' +
					'<h3 class="app-confirm__title"></h3>' +
					'<p class="app-confirm__message"></p>' +
					'<div class="app-confirm__actions">' +
						'<button type="button" class="app-confirm__button app-confirm__button--cancel">Batal</button>' +
						'<button type="button" class="app-confirm__button app-confirm__button--confirm">Ya</button>' +
					'</div>' +
				'</div>';

			modal = backdrop.querySelector('.app-confirm');
			iconEl = backdrop.querySelector('.app-confirm__icon');
			titleEl = backdrop.querySelector('.app-confirm__title');
			messageEl = backdrop.querySelector('.app-confirm__message');
			cancelBtn = backdrop.querySelector('.app-confirm__button--cancel');
			confirmBtn = backdrop.querySelector('.app-confirm__button--confirm');

			cancelBtn.addEventListener('click', function(){
				closeConfirm(false);
			});

			confirmBtn.addEventListener('click', function(){
				closeConfirm(true);
			});

			backdrop.addEventListener('click', function(event){
				if (event.target === backdrop){
					closeConfirm(false);
				}
			});

			document.addEventListener('keydown', function(event){
				if (!backdrop.classList.contains('is-active')) {
					return;
				}
				if (event.key === 'Escape') {
					closeConfirm(false);
				}
				if (event.key === 'Enter') {
					closeConfirm(true);
				}
			});

			document.body.appendChild(backdrop);
			return backdrop;
		}

		var resolveCallback = null;

		function openConfirm(options){
			ensureModal();

			var type = options.type || 'warning';
			modal.className = 'app-confirm app-confirm--' + type;
			iconEl.innerHTML = ICON_MAP[type] || ICON_MAP.warning;
			titleEl.textContent = options.title || 'Konfirmasi';
			messageEl.textContent = options.message || 'Yakin ingin melanjutkan tindakan ini?';
			cancelBtn.textContent = options.cancelText || 'Batal';
			confirmBtn.textContent = options.confirmText || 'Ya';

			backdrop.classList.add('is-active');
			setTimeout(function(){
				confirmBtn.focus();
			}, 50);

			return new Promise(function(resolve){
				resolveCallback = resolve;
			});
		}

		function closeConfirm(agree){
			if (!backdrop) {
				return;
			}
			backdrop.classList.remove('is-active');
			if (typeof resolveCallback === 'function') {
				resolveCallback({
					agree: agree === true,
					trigger: activeTrigger
				});
			}
			resolveCallback = null;
			activeTrigger = null;
		}

		function proceed(trigger){
			if (!trigger) {
				return;
			}
			var href = trigger.getAttribute('href');
			var target = trigger.getAttribute('target');
			var form = trigger.form;

			if (form && trigger.type === 'submit') {
				form.submit();
				return;
			}

			if (href) {
				if (target === '_blank') {
					window.open(href, '_blank');
				} else {
					window.location.href = href;
				}
			} else {
				trigger.dispatchEvent(new CustomEvent('app:confirm:accepted', {
					detail: { trigger: trigger }
				}));
			}
		}

		document.addEventListener('click', function(event){
			var trigger = event.target.closest('[data-confirm]');
			if (!trigger) {
				return;
			}
			// Avoid immediate re-click on confirm button
			if (trigger === confirmBtn) {
				return;
			}
			event.preventDefault();
			event.stopPropagation();

			activeTrigger = trigger;
			var options = {
				title: trigger.getAttribute('data-confirm-title') || 'Konfirmasi',
				message: trigger.getAttribute('data-confirm') || 'Yakin ingin melanjutkan?',
				confirmText: trigger.getAttribute('data-confirm-yes') || 'Ya',
				cancelText: trigger.getAttribute('data-confirm-no') || 'Batal',
				type: trigger.getAttribute('data-confirm-type') || 'warning'
			};

			openConfirm(options).then(function(result){
				if (result && result.agree) {
					proceed(result.trigger);
				}
			});
		});
	})();
	</script>
	<?php
	$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
	if ($flash) {
		$toastData = $flash;
		$printId = null;
		if (isset($toastData['print'])) {
			$printId = $toastData['print'];
			unset($toastData['print']);
		}
		unset($_SESSION['flash']);
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
				<?php if (!empty($printId)) { ?>
				setTimeout(function(){
					window.open('struk/struk.php?idt=' + <?php echo json_encode($printId); ?>, '_blank');
				}, 250);
				<?php } ?>
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
