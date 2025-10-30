<?php
	include "system/proses.php";

	$tgl = date('Y-m-d');

	$nomot = "TR001";
	try {
		$hasil = $db->get("MAX(id_transaksi) AS maxKode", "transaksi");
		$data = $hasil ? $hasil->fetch(PDO::FETCH_ASSOC) : null;
		$kodebarang = $data && !empty($data['maxKode']) ? $data['maxKode'] : null;
		if ($kodebarang) {
			$angkaTerakhir = (int) substr($kodebarang, 2);
			$angkaTerakhir++;
			$nomot = "TR" . sprintf("%03d", $angkaTerakhir);
		}
	} catch (Exception $e) {
		// fallback ke default ketika query gagal
		$nomot = "TR001";
	}

	$id_ptg = $_SESSION['login_id'];

	if(!function_exists('slugify_pos_category')){
		function slugify_pos_category($text){
			$text = strtolower(trim($text ?? ''));
			$text = preg_replace('/[^a-z0-9]+/', '-', $text);
			$text = trim($text, '-');
			return $text !== '' ? $text : 'lainnya';
		}
	}

	if(!function_exists('lighten_pos_color')){
		function lighten_pos_color($hex, $percent = 0.5){
			$hex = ltrim($hex ?? '', '#');
			if(strlen($hex) === 3){
				$hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
			}
			if(strlen($hex) !== 6){
				return '#9ca3af';
			}
			$rgb = [
				hexdec(substr($hex, 0, 2)),
				hexdec(substr($hex, 2, 2)),
				hexdec(substr($hex, 4, 2))
			];
			foreach($rgb as &$component){
				$component = (int) round($component + (255 - $component) * $percent);
				$component = max(0, min(255, $component));
			}
			return sprintf('#%02x%02x%02x', $rgb[0], $rgb[1], $rgb[2]);
		}
	}

	$produk = [];
	$kategoriList = [];
	try {
		$produkStmt = $db->get(
			"barang.id_brg, barang.nama_brg, barang.harga, barang.id_jenis_brg, jenis.nama_jenis",
			"barang",
			"LEFT JOIN jenis ON barang.id_jenis_brg = jenis.id_jenis ORDER BY jenis.nama_jenis ASC, barang.nama_brg ASC"
		);
		if($produkStmt){
			$produk = $produkStmt->fetchAll(PDO::FETCH_ASSOC);
			foreach($produk as $item){
				$label = $item['nama_jenis'] ?? 'Lainnya';
				$slug = slugify_pos_category($label);
				if(!isset($kategoriList[$slug])){
					$kategoriList[$slug] = $label;
				}
			}
		}
	} catch (Exception $e) {
		$produk = [];
		$kategoriList = [];
	}

	$colorMap = [
		'makanan' => '#f97316',
		'minuman' => '#0ea5e9',
		'sembako' => '#10b981',
		'peralatan-rumah' => '#8b5cf6',
		'rumah-tangga' => '#6366f1',
		'lainnya' => '#64748b'
	];

	$pelangganList = [];
	try{
		$pelangganStmt = $db->get(
			"pelanggan.id_pelanggan, pelanggan.nama",
			"pelanggan",
			"ORDER BY pelanggan.nama ASC"
		);
		if($pelangganStmt){
			$pelangganList = $pelangganStmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}catch (Exception $e){
		$pelangganList = [];
	}
?>
		<div class="judul-content">
			<h1>Transaksi</h1>
		</div>
	<div class="pos-wrapper" data-pos-page="transaksi">
	<form action="crud/simpan_transaksi.php" method="POST" class="pos-form">
		<input type="hidden" name="id_user" value="<?php echo $id_ptg; ?>">
		<div class="pos-main-grid">
			<aside class="pos-panel pos-panel--left">
				<div class="pos-card pos-meta">
					<div class="pos-meta-grid">
						<div class="pos-field">
							<span class="pos-label">ID Transaksi</span>
							<input type="text" name="id_transaksi" id="id_transaksi" class="text disable" readonly value="<?= $nomot; ?>">
						</div>
						<div class="pos-field">
							<span class="pos-label">Tanggal</span>
							<input type="text" name="tanggal" id="tanggal" class="text disable" value="<?= $tgl; ?>" readonly>
						</div>
					</div>
				</div>

				<div class="pos-card pos-customer">
					<div class="pos-section-header pos-section-header--between">
						<div class="pos-section-title">
							<i class="fas fa-user-friends"></i>
							<span>Informasi Pelanggan</span>
						</div>
					</div>
					<div class="pos-field-group">
						<label class="pos-label">Kategori</label>
						<select class="textkecil" name="kategori" id="kategori" onchange="plgn()">
							<option disabled selected>--Pilih--</option>
							<option>Pelanggan</option>
							<option>Non Pelanggan</option>
						</select>
					</div>
					<div class="pos-field-group pos-customer-grid">
						<div>
							<label class="pos-label" id="id_plgn">ID Pelanggan</label>
							<select name="idplgnn" id="id_pelanggan" class="textkecil" onchange="idp()">
								<option value="">Pilih pelanggan...</option>
								<?php foreach($pelangganList as $pelanggan): ?>
									<option value="<?= htmlspecialchars($pelanggan['id_pelanggan']); ?>" data-nama="<?= htmlspecialchars($pelanggan['nama']); ?>">
										<?= htmlspecialchars($pelanggan['id_pelanggan'] . " • " . $pelanggan['nama']); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div>
							<label class="pos-label" id="nm_plgn">Nama Pelanggan</label>
							<input type="text" name="nama_pelanggan" id="nama_pelanggan" class="textkecil disable" readonly placeholder="Nama akan terisi otomatis">
						</div>
					</div>
				</div>

				<div class="pos-card pos-item" id="pos-item-card">
					<div class="pos-section-header pos-section-header--between">
						<div class="pos-section-title">
							<i class="fas fa-plus-square"></i>
							<span>Ringkasan Item</span>
						</div>
						<button type="button" class="pos-clear-selection" id="pos-clear-selection" title="Bersihkan pilihan" disabled>
							<i class="fas fa-times"></i>
						</button>
					</div>
					<div class="pos-item-placeholder" id="pos-item-placeholder">
						<i class="fas fa-mouse-pointer"></i>
						<p>Pilih produk dari katalog untuk menambahkan ke keranjang.</p>
					</div>
					<div class="pos-item-summary is-hidden" id="pos-item-summary">
						<div class="pos-item-summary-main">
							<div class="pos-item-summary-name" id="pos-item-summary-name">-</div>
							<div class="pos-item-summary-meta">
								<span class="pos-item-summary-category" id="pos-item-summary-category">-</span>
								<span class="pos-item-summary-price" id="pos-item-summary-price">Rp 0</span>
							</div>
						</div>
						<div class="pos-item-summary-actions">
							<div class="pos-item-qty-control pos-qty-control">
								<button type="button" class="pos-qty-btn" data-action="minus" aria-label="Kurangi jumlah">
									<i class="fas fa-minus"></i>
								</button>
								<input type="number" name="jumlah" id="jumlah" class="pos-summary-qty-input" min="1" value="1">
								<button type="button" class="pos-qty-btn" data-action="plus" aria-label="Tambah jumlah">
									<i class="fas fa-plus"></i>
								</button>
							</div>
							<div class="pos-item-summary-total">
								<span>Total</span>
								<strong id="pos-item-summary-total">Rp 0</strong>
							</div>
						</div>
					</div>
					<div class="pos-item-hidden-fields">
						<input type="hidden" name="id_barang" id="id_barang">
						<input type="hidden" name="nama_barang" id="nama_barang">
						<input type="hidden" name="harga" id="harga">
						<input type="hidden" name="total" id="total">
					</div>
					<div class="pos-action">
						<button type="button" class="simpantrans btn-biru" id="pos-add-button" onclick="simpan_detail()" disabled>
							<i class="fas fa-plus-circle"></i> Tambah ke Keranjang
						</button>
					</div>
				</div>

				<div class="pos-card pos-cart">
					<div class="pos-section-header pos-section-header--between">
						<div class="pos-section-title">
							<i class="fas fa-shopping-basket"></i>
							<span>Keranjang</span>
						</div>
					</div>
					<div id="kotak-detail" class="pos-cart-container"></div>
				</div>

			</aside>

			<section class="pos-panel pos-panel--right">
				<div class="pos-card pos-catalog">
					<div class="pos-product-grid" id="pos-product-grid">
						<?php if(empty($produk)): ?>
							<div class="pos-empty-state">
								<i class="fas fa-box-open"></i>
								<p>Belum ada produk yang tersedia.</p>
							</div>
						<?php else: ?>
							<?php foreach($produk as $item):
								$label = $item['nama_jenis'] ?? 'Lainnya';
								$slug = slugify_pos_category($label);
								$color = $colorMap[$slug] ?? $colorMap['lainnya'];
								$colorSoft = lighten_pos_color($color, 0.7);
							?>
								<button type="button"
									class="pos-product-card"
									data-id="<?= htmlspecialchars($item['id_brg']); ?>"
									data-name="<?= htmlspecialchars($item['nama_brg']); ?>"
									data-price="<?= (int) $item['harga']; ?>"
									data-category="<?= $slug; ?>"
									data-category-label="<?= htmlspecialchars($label); ?>"
									data-accent="<?= $color; ?>"
									data-accent-soft="<?= $colorSoft; ?>"
									onclick="selectCatalogProduct(this)"
									style="--accent-color: <?= $color; ?>; --accent-soft: <?= $colorSoft; ?>;">
									<span class="pos-product-heading">
										<span class="pos-product-name"><?= htmlspecialchars($item['nama_brg']); ?></span>
										<span class="pos-product-price">Rp <?= number_format((int) $item['harga'], 0, ',', '.'); ?></span>
									</span>
									<span class="pos-product-footer">
										<span class="pos-product-category"><?= htmlspecialchars($label); ?></span>
										<span class="pos-product-add">
											<i class="fas fa-plus"></i>
											Tambah
										</span>
									</span>
								</button>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			<div class="pos-card pos-payment">
				<div class="pos-section-header pos-section-header--between">
					<div class="pos-section-title">
						<i class="fas fa-cash-register"></i>
						<span>Pembayaran</span>
					</div>
				</div>
				<div class="pos-payment-summary">
					<div class="pos-payment-row">
						<div class="pos-payment-label">Sub Total</div>
						<div class="pos-payment-value"><input type="text" name="subtotal" id="subtotal" class="textkecil disable" readonly></div>
					</div>
					<div class="pos-payment-row">
						<div class="pos-payment-label">Diskon</div>
						<div class="pos-payment-value"><input type="text" name="diskon" id="diskon" class="textkecil disable" readonly></div>
					</div>
					<div class="pos-payment-row is-total">
						<div class="pos-payment-label">Total Bayar</div>
						<div class="pos-payment-value"><input type="text" name="totalbayar" id="totalbayar" class="textkecil disable" readonly></div>
					</div>
					<div class="pos-payment-row is-highlight">
						<div class="pos-payment-label">Bayar</div>
						<div class="pos-payment-value"><input type="text" name="bayar" id="bayar" class="textkecil" onkeyup="byr()" autocomplete="off" placeholder="Masukkan nominal pembayaran" required></div>
					</div>
					<div class="pos-payment-row">
						<div class="pos-payment-label">Kembali</div>
						<div class="pos-payment-value"><input type="text" name="kembali" id="kembali" class="textkecil disable" readonly></div>
					</div>
				</div>
				<div class="pos-payment-cta">
					<div class="pos-action pos-payment-action">
						<button type="submit" name="simpan" class="simpantrans">
							<i class="fas fa-receipt"></i> Selesaikan Transaksi
						</button>
					</div>
				</div>
			</div>
			</section>
		</div>
	</form>
</div>
