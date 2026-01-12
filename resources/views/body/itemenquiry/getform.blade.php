<style type="text/css" media="print">
   /* .page
    {
     -webkit-transform: rotate(-90deg); 
     -moz-transform:rotate(-90deg);
     filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    } */
</style>
<style>
    .center {
        display: block;
        flex-direction: column;
        align-items: flex-start;
        border:0px solid black;
        text-align: center;
        margin: auto;
        font-family: Arial, Helvetica, sans-serif;

        }

        .center span{
            display: block;
            text-align:centre;
        }
        
        #com{
            font-weight:bold;
            font-size:16pt;
            margin: -2px;
        }
        
        #des{
            margin: 0px;
            font-size:8pt;
        }
        
         #prc{
            margin: 0px;
            font-size:10pt;
        }
        
        #cod{
            font-weight:bold;
            font-size:8pt;
            margin: 1px;
        }

        @media print {
        body {
            visibility: hidden;
        }
        #bcode {
            visibility: visible;
            position: absolute;
            left: 0;
            top: 0;
        }
        }
</style>

<div class="page">
<h1>Barcode Print</h1><hr/>
<table>
    <tr><td>Size</td><td>
        <select id="size"><option value="S">Small</option><option value="M">Medium</option><option value="L">Large</option></select>
    </tr>
    <tr><td>Item Code</td><td>
        <select id="icode"><option value="1">Yes</option><option value="0">No</option></select>
    </tr>
    <tr><td>Company Name</td><td>
        <select id="cname"><option value="1">Yes</option><option value="0">No</option><</select>
    </tr>
    <tr><td>Description</td><td>
        <select id="descr"><option value="0">No</option><option value="1">Yes</option></select>
    </tr>
    <tr><td>Price</td><td>
        <select id="price"><option value="0">No</option><option value="1">Yes</option></select>
    </tr>
    
    <tr><td>No of Copy</td><td><input type="text" name="copy" id="copy"></tr>
    <tr><td></td><td><button onClick="window.print()">Print</button> </tr>
</table>
<div id="bcode" class="center">
	<?php
		echo '<span id="com">'.Session::get('company').'</span>';
		echo '<span id="des" >'.$item->description.'</span>';
        echo '<span id="prc"><b>AED:'.number_format($item->sell_price,2).'</b></span>';
        //echo '<span>'.$item->description.'</span>';
		//echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", "C39+",1.5,96) . '" id="barcode" alt="barcode" width="128" height="40" />';
        echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", 'C128A') . '" id="barcode" alt="barcode" width="128" height="30" />';
		//echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", 'C128') . '" id="barcode" alt="barcode" width="128" height="40" />';
            //'<img src="data:image/png;base64,' . DNS1D::getBarcodePNG('4', 'C39+') . '" alt="barcode"   />';
        
        echo '<span id="cod">'.$item->item_code.'</span>';
	?>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $("#bcode").width(143); $("#bcode").height(94);
   // $('#com').toggle();//$('#prc').toggle();
    $(document).on('change','#icode', function() {   $('#cod').toggle();  });
    $(document).on('change','#cname', function() {   $('#com').toggle();  });
    $(document).on('change','#price', function() {   $('#prc').toggle();  });
    $(document).on('change','#descr', function() {   $('#cod').toggle();  });

    $(document).on('change','#size', function() { 
        if($(this).val()=="S") {
            $("#bcode").width(143); $("#bcode").height(94);
            $("#barcode").width(128); $("#barcode").height(25);
        } else if($(this).val()=="M"){
            $("#bcode").width(188); $("#bcode").height(94);
            $("#barcode").width(170); $("#barcode").height(45);
        }else if($(this).val()=="L") {
            $("#bcode").width(219); $("#bcode").height(147);
            $("#barcode").width(200); $("#barcode").height(80);
        }

       //143.62204724 x 94.488188976
       //188.97637795 x 94.488188976
       //219.21259843 x 147.4015748
    })
</script>
