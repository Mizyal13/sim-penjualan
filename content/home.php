<div class="judul-content">
	<h1>Beranda</h1>
</div>

<div class="isi-content">
	<div class="home-container">
		<div class="welcome-card tilt-card" data-float="22">
			<h1>👋 Selamat Datang di <span>SIM Penjualan</span></h1>
			<p>Kelola data barang, pelanggan, dan transaksi Anda dengan mudah dan efisien.</p>
		</div>

		<div class="stat-container">
			<a href="index.php?p=barang" class="stat-card tilt-card" data-float="14">
				<i class="fas fa-box"></i>
				<p>Data Barang</p>
			</a>
			<a href="index.php?p=pelanggan" class="stat-card tilt-card" data-float="18">
				<i class="fas fa-users"></i>
				<p>Pelanggan</p>
			</a>
			<a href="index.php?p=transaksi" class="stat-card tilt-card" data-float="20">
				<i class="fas fa-shopping-cart"></i>
				<p>Transaksi</p>
			</a>
		</div>
	</div>
</div>

<style>
	.home-container {
		background: linear-gradient(135deg, #e3f2fd, #ffffff);
		padding: 30px;
		border-radius: 15px;
		box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
		text-align: center;
	}

	.welcome-card h1 {
		font-size: 28px;
		font-weight: bold;
		color: #0d47a1;
	}

	.welcome-card span {
		color: #1565c0;
	}

	.welcome-card p {
		font-size: 16px;
		color: #333;
		margin-top: 10px;
	}

	.stat-container {
		display: flex;
		justify-content: center;
		gap: 20px;
		margin-top: 30px;
		flex-wrap: wrap;
	}

	.stat-card {
		background: white;
		padding: 20px 30px;
		border-radius: 12px;
		box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
		width: 180px;
		transition: 0.3s;
		text-decoration: none;
		color: inherit;
		display: block;
	}

	.stat-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
	}

	.stat-card i {
		font-size: 30px;
		color: #1976d2;
		margin-bottom: 10px;
	}

	.stat-card h3 {
		font-size: 22px;
		margin: 0;
		color: #0d47a1;
	}

	.stat-card p {
		font-size: 14px;
		color: #555;
	}
</style>
