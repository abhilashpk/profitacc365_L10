<div align="center">
	<?php
		echo '<p>'.$item->description.'</p>';
		echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", "C39",1.5,96) . '" alt="barcode"   /><br/>';
		//echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG("$item->item_code", "C39+",1.5,96) . '" alt="barcode"   /><br/>';
		echo $item->item_code;
	?>
</div>