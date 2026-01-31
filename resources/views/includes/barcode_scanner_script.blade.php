<script>
(function() {
	var invoiceFormSelector = window.invoiceFormSelector || '#frmSalesInvoice';
	var barcodeScannerEnabled = window.barcodeScannerEnabled || false;
	if(!barcodeScannerEnabled) return;

	var barcodeBuffer = '';
	var barcodeTimer = null;
	var lastKeyTs = 0;
	var barcodeMinLen = 6;
	var barcodeMaxGapMs = 50;

	$(document).on('keydown', function(e) {
		var $focused = $(':focus');
		if ($focused.length && $focused.attr('name') === 'item_code[]') {
			return;
		}
		var key = e.which || e.keyCode;
		var now = Date.now();
		var gap = now - lastKeyTs;
		lastKeyTs = now;

		if (gap > barcodeMaxGapMs) {
			barcodeBuffer = '';
		}

		if (key === 13 || key === 9) {
			if (barcodeBuffer.length >= barcodeMinLen) {
				e.preventDefault();
				e.stopPropagation();
				processBarcodeScan(barcodeBuffer);
				barcodeBuffer = '';
			}
			return;
		}

		if (key < 32 || e.ctrlKey || e.altKey || e.metaKey) return;

		var ch = String.fromCharCode(key);
		barcodeBuffer += ch;

		clearTimeout(barcodeTimer);
		barcodeTimer = setTimeout(function() {
			if (barcodeBuffer.length >= barcodeMinLen) {
				processBarcodeScan(barcodeBuffer);
			}
			barcodeBuffer = '';
		}, 80);
	});

	$(document).on('keydown', 'input[name="item_code[]"]', function(e) {
		var key = e.which || e.keyCode;
		if (key !== 13 && key !== 9) return;
		var code = $.trim($(this).val());
		if(code === '') return;
		e.preventDefault();
		e.stopPropagation();
		barcodeBuffer = '';
		clearTimeout(barcodeTimer);
		var row = getRowIndexFromInput($(this));
		if(!row) return;
		handleScannedCode($(this), row, code);
	});

	function processBarcodeScan(code) {
		var row = getActiveRowForScan();
		if(!row) return;
		handleScannedCode($('#itmcod_'+row), row, code);
	}

	function getActiveRowForScan() {
		var $focused = $(':focus');
		if ($focused.length && $focused.attr('name') === 'item_code[]') {
			return getRowIndexFromInput($focused);
		}
		var emptyRow = null;
		$('.itemdivPrnt .itemdivChld').each(function() {
			var idx = getRowIndexFromInput($(this).find('input[name="item_code[]"]'));
			if(!idx) return;
			if(getRowItemId(idx) === '' && getRowCode(idx) === '') {
				emptyRow = idx;
				return false;
			}
		});
		if (emptyRow) return emptyRow;
		$('.itemdivPrnt .itemdivChld:last').find('.btn-add-item').click();
		return rowNum;
	}

	function handleScannedCode($input, row, code) {
		var currentCode = getRowCode(row);
		var currentItemId = getRowItemId(row);
		if(currentItemId && currentCode === code) {
			incrementRowQuantity(row);
			$input.val('');
			focusRowInput(row);
			return;
		}
		if(currentItemId && currentCode !== '') {
			row = findOrCreateEmptyRow(row);
			$input = $('#itmcod_'+row);
		}
		var duplicate = findRowWithCode(code, row);
		if(duplicate) {
			incrementRowQuantity(duplicate);
			$input.val('');
			focusRowInput(row);
			return;
		}
		loadItemByCode(row, code);
	}

	$(invoiceFormSelector).on('submit', function() {
		$('.itemdivPrnt .itemdivChld').each(function() {
			var $code = $(this).find('input[name="item_code[]"]');
			var $itemId = $(this).find('input[name="item_id[]"]');
			if($code.length && $itemId.length &&
				$.trim($itemId.val()) === '' &&
				$.trim($code.val()) === '') {
				$(this).remove();
			}
		});
		return true;
	});

	function loadItemByCode(row, code) {
		$('#itmcod_'+row).val(code);
		var url = "{{ url('itemmaster/item_load') }}/" + encodeURIComponent(code);
		$.get(url, function(data) {
			if(!data || !data.id) {
				showScanWarning('Item not found for code: ' + code);
				clearItemRow(row);
				return;
			}
			fillItemRow(row, data, code);
			focusNextRow(row);
		}).fail(function() {
			showScanWarning('Item not found for code: ' + code);
			clearItemRow(row);
		});
	}

	function fillItemRow(row, item, code) {
		$('#itmid_'+row).val(item.id);
		$('#itmdes_'+row).val(item.description);
		$('#itmcod_'+row).val(code);
		srvat = parseFloat(item.vat || 0);
		$('#vatdiv_'+row).val(srvat+'%');
		$('#vat_'+row).val(srvat);
		var cost = '';
		if(item.cost_avg) cost = parseFloat(item.cost_avg);
		else if(item.pur_cost) cost = parseFloat(item.pur_cost);
		cost = (cost !== '' && !isNaN(cost)) ? cost.toFixed(2) : '';
		$('#itmcst_'+row).val(cost);
		var $qty = $('#itmqty_'+row);
		if($qty.val()=='' || parseFloat($qty.val())===0) $qty.val(1);
		loadUnitsForRow(row, item.id, item.unit_id);
		getLineTotal(row);
		getNetTotal();
	}

	function loadUnitsForRow(row, itemId, unitId) {
		$.get("{{ url('purchase_order/getunit/') }}/" + itemId, function(data) {
			var select = $('#itmunt_'+row);
			select.find('option').remove();
			select.append('<option value="">Unit</option>');
			$.each(data, function(key, value) {
				select.append('<option value="'+value.id+'">'+value.unit_name+'</option>');
			});
			if(unitId) {
				select.val(unitId);
				$('#hidunit_'+row).val(select.find(':selected').text());
			}
		});
	}

	function findRowWithCode(code, ignoreRow) {
		var found = null;
		$('.itemdivPrnt .itemdivChld').each(function() {
			var $input = $(this).find('input[name="item_code[]"]');
			var idx = getRowIndexFromInput($input);
			if(!idx || idx === ignoreRow) return;
			if(getRowItemId(idx) && $.trim($input.val()) === code) {
				found = idx;
				return false;
			}
		});
		return found;
	}

	function getRowCode(row) {
		return $.trim($('#itmcod_'+row).val());
	}

	function getRowItemId(row) {
		return $.trim($('#itmid_'+row).val());
	}

	function clearItemRow(row) {
		$('#itmid_'+row).val('');
		$('#itmcod_'+row).val('');
		$('#itmdes_'+row).val('');
		$('#itmqty_'+row).val('');
		$('#itmcst_'+row).val('');
		$('#vatdiv_'+row).val('');
		$('#vat_'+row).val('');
		$('#vatlineamt_'+row).val('');
		$('#itmttl_'+row).val('');
		$('#itmtot_'+row).val('');
		$('#itmunt_'+row).find('option').remove().end().append('<option value="">Unit</option>');
	}

	function showScanWarning(message) {
		var $toast = $('#barcode_warning');
		if(!$toast.length) {
			$('body').append('<div id="barcode_warning" style="position:fixed;top:20px;right:20px;background:#f2dede;color:#a94442;border:1px solid #ebccd1;padding:8px 12px;border-radius:4px;z-index:9999;display:none;"></div>');
			$toast = $('#barcode_warning');
		}
		$toast.stop(true, true).text(message).fadeIn(120);
		setTimeout(function() {
			$toast.fadeOut(300);
		}, 1500);
	}

	function findOrCreateEmptyRow(afterRow) {
		var start = afterRow;
		var target = null;
		$('.itemdivPrnt .itemdivChld').each(function() {
			var idx = getRowIndexFromInput($(this).find('input[name="item_code[]"]'));
			if(!idx || idx <= start) return;
			if(getRowCode(idx) === '') {
				target = idx;
				return false;
			}
		});
		if(!target) {
			$('.itemdivPrnt .itemdivChld:last').find('.btn-add-item').click();
			target = rowNum;
		}
		return target;
	}

	function incrementRowQuantity(row) {
		var $qty = $('#itmqty_'+row);
		var current = parseFloat($qty.val() || 0);
		current = isNaN(current) ? 0 : current;
		$qty.val((current + 1).toFixed(2).replace(/\.00$/, ''));
		getLineTotal(row);
		getNetTotal();
	}

	function focusNextRow(row) {
		var next = row + 1;
		if(!$('#itmcod_'+next).length) {
			$('.itemdivPrnt .itemdivChld:last').find('.btn-add-item').click();
			next = rowNum;
		}
		$('#itmcod_'+next).focus();
	}

	function focusRowInput(row) {
		if($('#itmcod_'+row).length) {
			$('#itmcod_'+row).focus();
		}
	}

	function getRowIndexFromInput($input) {
		var id = $input.attr('id') || '';
		var parts = id.split('_');
		var idx = parseInt(parts[1], 10);
		return isNaN(idx) ? null : idx;
	}

})();
</script>
