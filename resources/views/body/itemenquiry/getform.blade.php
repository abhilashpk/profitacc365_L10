<style>
    :root {
        --label-w: 38mm;
        --label-h: 25mm;
        --barcode-w: 34mm;
        --barcode-h: 10mm;
    }

    @page {
        size: var(--label-w) var(--label-h);
        margin: 0;
    }

    html, body {
        margin: 0;
        padding: 0;
    }
    .center {
        display: flex;
        flex-direction: column;
        border: 0 solid black;
        text-align: center;
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        width: var(--label-w);
        height: var(--label-h);
        overflow: hidden;
        align-items: center;
        justify-content: center;
    }

    .form-layout {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .form-controls {
        min-width: 260px;
    }

    .preview-wrap {
        flex: 1;
        display: flex;
        justify-content: center;
    }

        .center span{
            display: block;
            text-align: center;
        }
        
    #com{
        font-weight: bold;
        font-size: 10pt;
        margin: 0;
    }
        
    #des{
        margin: 0;
        font-size: 7pt;
        margin-bottom: 2mm;
        word-wrap: break-word;
    }
        
    #prc{
        margin: 0;
        font-size: 9pt;
    }
        
    #cod{
        font-weight: bold;
        font-size: 8pt;
        margin: 0;
        letter-spacing: normal;
        display: inline-block;
        white-space: nowrap;
    }

    #barcode {
        width: var(--barcode-w);
        height: var(--barcode-h);
    }

    @media print {
        html, body {
            margin: 0;
            padding: 0;
        }
        .page {
            margin: 0;
            padding: 0;
        }
        #bcode-container {
            display: block;
            width: var(--label-w);
        }
        #bcode, .bcode-copy {
            width: var(--label-w);
            height: var(--label-h);
            margin: 0;
            page-break-after: always;
        }
        #bcode {
            position: relative;
            left: 0;
            top: 0;
        }
        .no-print {
            display: none;
        }
        }
</style>

<div class="page">
<div class="no-print form-layout">
<div class="form-controls">
<h1>Barcode Print</h1><hr/>
<table>
    <tr><td>Size</td><td>
        <select id="size">
            <option value="S" data-w="38" data-h="25" data-bw="34" data-bh="10">Small (38x25mm)</option>
            <option value="M" data-w="50" data-h="25" data-bw="46" data-bh="14">Medium (50x25mm)</option>
            <option value="L" data-w="58" data-h="38" data-bw="54" data-bh="22">Large (58x38mm)</option>
        </select>
    </tr>
    <tr><td>Item Code</td><td>
        <select id="icode"><option value="1">Yes</option><option value="0">No</option></select>
    </tr>
    <tr><td>Company Name</td><td>
        <select id="cname"><option value="0" selected>No</option><option value="1">Yes</option></select>
    </tr>
    <tr><td>Description</td><td>
        <select id="descr"><option value="1">Yes</option><option value="0">No</option></select>
    </tr>
    <tr><td>Price</td><td>
        <select id="price"><option value="0" selected>No</option><option value="1">Yes</option></select>
    </tr>
    
    <tr><td>No of Copy</td><td><input type="text" name="copy" id="copy"></tr>
    <tr><td></td><td><button type="button" id="btnPrint">Print</button> </tr>
</table>
</div>
</div>
<div class="preview-wrap">
<div id="bcode-container">
<div id="bcode" class="center">
	<?php
		echo '<span id="com">'.Session::get('company').'</span>';
		echo '<span id="des" >'.$item->description.'</span>';
        echo '<span id="prc"><b>AED:'.number_format($item->sell_price,2).'</b></span>';
        //echo '<span>'.$item->description.'</span>';
		//echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", "C39+",1.5,96) . '" id="barcode" alt="barcode" width="128" height="40" />';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", 'C128') . '" id="barcode" alt="barcode" />';
		//echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", 'C128') . '" id="barcode" alt="barcode" width="128" height="40" />';
            //'<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
        
        echo '<span id="cod">'.$item->item_code.'</span>';
	?>
</div>
</div>
</div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    function applySizeFromOption($opt) {
        var w = $opt.data('w');
        var h = $opt.data('h');
        var bw = $opt.data('bw');
        var bh = $opt.data('bh');

        document.documentElement.style.setProperty('--label-w', w + 'mm');
        document.documentElement.style.setProperty('--label-h', h + 'mm');
        document.documentElement.style.setProperty('--barcode-w', bw + 'mm');
        document.documentElement.style.setProperty('--barcode-h', bh + 'mm');

        $('#barcode').css({ width: bw + 'mm', height: bh + 'mm' });
        updateCodeSpacing();
    }

    // Initialize with default size
    applySizeFromOption($('#size option:selected'));
    // Default hide company name and price in preview
    $('#com').hide();
    $('#prc').hide();

    function updateCodeSpacing() {
        var $code = $('#cod');
        var $barcode = $('#barcode');
        if ($code.length === 0 || $barcode.length === 0) return;

        var text = $code.text() || '';
        var len = text.length;
        if (len <= 1) {
            $code.css('letter-spacing', 'normal');
            return;
        }

        $code.css('letter-spacing', 'normal');
        var barcodeWidth = $barcode[0].getBoundingClientRect().width;
        var textWidth = $code[0].getBoundingClientRect().width;
        var extra = barcodeWidth - textWidth;
        var spacing = extra > 0 ? (extra / (len - 1)) : 0;
        $code.css('letter-spacing', spacing + 'px');
    }

    $(document).on('change','#icode', function() { $('#cod').toggle(); updateCodeSpacing(); });
    $(document).on('change','#cname', function() { $('#com').toggle(); });
    $(document).on('change','#price', function() { $('#prc').toggle(); });
    $(document).on('change','#descr', function() { $('#des').toggle(); });

    $(document).on('change','#size', function() { 
        applySizeFromOption($('#size option:selected'));

       //143.62204724 x 94.488188976
       //188.97637795 x 94.488188976
       //219.21259843 x 147.4015748
    });

    function buildCopies() {
        var count = parseInt($('#copy').val(), 10);
        if (isNaN(count) || count < 1) count = 1;
        if (count > 50) count = 50; // basic safety

        var $container = $('#bcode-container');
        $container.find('.bcode-copy').remove();
        for (var i = 1; i < count; i++) {
            var $clone = $('#bcode').clone(true, true);
            $clone.addClass('bcode-copy');
            $clone.removeAttr('id');
            $clone.css({ position: 'relative', left: 'auto', top: 'auto' });
            $container.append($clone);
        }
    }

    $('#btnPrint').on('click', function() {
        buildCopies();
        window.print();
    });
</script>
