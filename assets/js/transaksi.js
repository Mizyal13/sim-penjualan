function plgn(){
	var plh = $('#kategori').val();
	var $idWrapper = $('#id_pelanggan').closest('div');
	var $nameWrapper = $('#nama_pelanggan').closest('div');

	if(plh === "Pelanggan"){
		$('#id_plgn, #nm_plgn').show("slow");
		$idWrapper.show("slow");
		$nameWrapper.show("slow");
		$('#id_pelanggan').prop('disabled', false);
	}else if(plh === "--Pilih--" || !plh){
		$('#id_plgn, #nm_plgn').show("slow");
		$idWrapper.show("slow");
		$nameWrapper.show("slow");
		$('#id_pelanggan').prop('disabled', true).val("");
		$('#nama_pelanggan').val("");
	}else{
		$('#id_plgn, #nm_plgn').hide("slow");
		$idWrapper.hide("slow");
		$nameWrapper.hide("slow");
		$('#id_pelanggan').prop('disabled', true).val("");
		$('#nama_pelanggan').val("");
	}
	idp();
	updateAddButtonState();
}


// Pelanggan Otomatis
function idp(){
	var $selected = $('#id_pelanggan option:selected');
	if(!$selected.length || !$selected.val()){
		$('#nama_pelanggan').val("");
		return;
	}
	var nama = $selected.data('nama') || "";
	$('#nama_pelanggan').val(nama);
}

// Barang Otomatis
function idb(){
	$.ajax({
		url:"content/cari_brg.php",
		type:"POST",
		dataType:"json",
		data:{
			id_barang:$('#id_barang').val()
		},
		success:function(hasil){
			if(!hasil || hasil.id_barang === ""){
				$('#nama_barang').val("");
				$('#harga').val("");
				$('#total').val("");
				return;
			}
			$('#nama_barang').val(hasil.nama_barang);
			$('#harga').val(hasil.harga);
			t();
		}

	});
}

function updateAddButtonState(){
	var ready = selectedProduct !== null && $('#id_barang').val() !== "" && $('#jumlah').val() !== "" && $('#total').val() !== "";
	$('#pos-add-button').prop('disabled', !ready);
}

function formatCurrency(value){
	var number = parseFloat(value);
	if(isNaN(number)){
		number = 0;
	}
	return number.toLocaleString('id-ID');
}

var selectedProduct = null;

function renderSelectedProduct(){
	var $summary = $('#pos-item-summary');
	if(!selectedProduct){
		$('#id_barang').val("");
		$('#nama_barang').val("");
		$('#harga').val("");
		$('#total').val("");
		$('#jumlah').val(1);
		$('#pos-item-placeholder').removeClass('is-hidden');
		$summary.addClass('is-hidden').removeData('categoryLabel').css('--summary-accent', '').css('--summary-accent-soft', '');
		$('#pos-item-summary-name').text('-');
		$('#pos-item-summary-category').text('-');
		$('#pos-item-summary-price').text('Rp 0');
		$('#pos-item-summary-total').text('Rp 0');
		$('#pos-clear-selection').prop('disabled', true);
		updateAddButtonState();
		return;
	}

	var qty = parseInt(selectedProduct.qty, 10);
	if(isNaN(qty) || qty <= 0){
		qty = 1;
	}
	selectedProduct.qty = qty;

	var total = selectedProduct.price * qty;

	$('#id_barang').val(selectedProduct.id);
	$('#nama_barang').val(selectedProduct.name);
	$('#harga').val(selectedProduct.price);
	$('#jumlah').val(qty);
	$('#total').val(total);

	$('#pos-item-summary-name').text(selectedProduct.name);
	$('#pos-item-summary-category').text(selectedProduct.category || '-');
	$('#pos-item-summary-price').text('Rp ' + formatCurrency(selectedProduct.price));
	$('#pos-item-summary-total').text('Rp ' + formatCurrency(total));

	$('#pos-item-placeholder').addClass('is-hidden');
	$summary
		.removeClass('is-hidden')
		.data('categoryLabel', selectedProduct.category || '-')
		.css('--summary-accent', selectedProduct.accent || '#38bdf8')
		.css('--summary-accent-soft', selectedProduct.accentSoft || 'rgba(56,189,248,0.12)');

	$('#pos-clear-selection').prop('disabled', false);
	updateAddButtonState();
}

function resetActiveItem(){
	selectedProduct = null;
	$('.pos-product-card.is-selected').removeClass('is-selected');
	renderSelectedProduct();
}

function selectCatalogProduct(element){
	var $card = $(element);
	if(!$card || !$card.length){
		return;
	}

	var $grid = $('#pos-product-grid');
	if($grid.length){
		$grid.find('.pos-product-card').removeClass('is-selected');
	}
	$card.addClass('is-selected');

	var price = parseFloat($card.data('price')) || 0;
	var name = $card.data('name') || '';
	var accent = $card.data('accent') || '#38bdf8';
	var accentSoft = $card.data('accentSoft') || 'rgba(56,189,248,0.12)';
	var categoryLabel = $card.data('categoryLabel') || '-';

	selectedProduct = {
		id: $card.data('id'),
		name: name,
		price: price,
		category: categoryLabel,
		accent: accent,
		accentSoft: accentSoft,
		qty: 1
	};

	renderSelectedProduct();
	$('#jumlah').focus();
}

function setupProductCatalog(){
	var $grid = $('#pos-product-grid');
	if(!$grid.length){
		return;
	}
	$grid.off('click.posCatalog').on('click.posCatalog', '.pos-product-card', function(e){
		e.preventDefault();
		selectCatalogProduct(this);
	});

	$grid.toggleClass('is-empty', !$grid.find('.pos-product-card').length);
}



function hapus_detail(h){

	$.ajax({
		url:"crud/hapus_detail.php",
		type:"POST",
		dataType:"json",
		data:{
			id_detail_transaksi:h
		},
		success:function(res){
			if(window.AppToast){
				AppToast.show({
					message: res && res.message ? res.message : "Detail transaksi berhasil dihapus.",
					type: res && res.success ? "success" : "error"
				});
			}
			if(res && res.success){
				buka_tab();
			}
		},
		error:function(xhr){
			var msg = "Gagal menghapus data. Silakan coba lagi.";
			if(xhr.responseJSON && xhr.responseJSON.message){
				msg = xhr.responseJSON.message;
			}
			if(window.AppToast){
				AppToast.show({
					message: msg,
					type: "error"
				});
			}
		}

	});
}


// Total
function t(){
	var jml = parseInt($('#jumlah').val(), 10);
	if(isNaN(jml) || jml <= 0){
		jml = 1;
		$('#jumlah').val(jml);
	}

	if(selectedProduct){
		selectedProduct.qty = jml;
		renderSelectedProduct();
	}else{
		var hrg = parseFloat($('#harga').val());
		if(isNaN(hrg)){
			$('#total').val("");
			updateAddButtonState();
			return;
		}
		var tot = hrg * jml;
		$('#total').val(tot);
		updateAddButtonState();
	}
}


// Simpan Detail
function simpan_detail(){
	if($('#id_barang').val() === "" || $('#jumlah').val() === "" || $('#total').val() === ""){
		if(window.AppToast){
			AppToast.show({
				message: "Lengkapi data barang dan jumlah terlebih dahulu.",
				type: "error"
			});
		}
		return;
	}
	$.ajax({
		url:"crud/simpan_detail.php",
		type:"POST",
		dataType:"json",
		data:{
			id_transaksi:$('#id_transaksi').val(),
			id_barang:$('#id_barang').val(),
			jumlah_beli:$('#jumlah').val(),
			total:$('#total').val()
		},
		success:function(res){
			var msg = res && res.message ? res.message : "Detail transaksi berhasil disimpan.";
			var type = res && res.success ? "success" : "error";

		if(window.AppToast){
			AppToast.show({
				message: msg,
				type: type
			});
		}

		if(res && res.success){
			buka_tab();
			resetActiveItem();
			var $searchField = $('#pos-product-search');
			if($searchField.length){
				$searchField.focus();
			}
		}
		},
		error:function(xhr){
			var msg = "Gagal menyimpan detail transaksi. Silakan coba lagi.";
			if(xhr.responseJSON && xhr.responseJSON.message){
				msg = xhr.responseJSON.message;
			}
			if(window.AppToast){
				AppToast.show({
					message: msg,
					type: "error"
				});
			}
		}

	});
}


function buka_tab(){
	id = $('#id_transaksi').val();
	$('#kotak-detail').load('content/detail_trans.php?idt='+id);

}


function byr(){
	b = $('#bayar').val();
	tb = $('#totalbayar').val();
	rsl = b-tb;
	$('#kembali').val(rsl);
}
$(document).ready(function(){
	$('#lpr1').hide();
	$('#lpr2').hide();
	$('#lpr3').hide();
	$('#lpr').click(function(){
		$('#lpr1').slideToggle("slow");
		$('#lpr2').slideToggle("slow");
		$('#lpr3').slideToggle("slow");
	});

	resetActiveItem();
	setupProductCatalog();

	$('.pos-qty-btn').on('click', function(){
		var action = $(this).data('action');
		var $qty = $('#jumlah');
		var current = parseInt($qty.val(), 10) || 1;
		if(action === "minus"){
			current = Math.max(1, current - 1);
		}else{
			current += 1;
		}
		$qty.val(current);
		t();
	});

	$('#jumlah').on('input', function(){
		var val = parseInt($(this).val(), 10);
		if(isNaN(val) || val <= 0){
			val = 1;
		}
		$(this).val(val);
		t();
	});

	$('#pos-clear-selection').on('click', function(){
		resetActiveItem();
	});

	plgn();
});

if(typeof window !== 'undefined'){
	window.selectCatalogProduct = selectCatalogProduct;
	document.addEventListener('click', function(evt){
		var card = evt.target.closest('.pos-product-card');
		if(card && !evt.defaultPrevented){
			evt.preventDefault();
			selectCatalogProduct(card);
		}
	});
}
