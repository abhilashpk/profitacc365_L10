<?php if($type=='ITEM') { ?>
<span>Items:</span> <br/>
<select id="select1" multiple style="width:100%" class="form-control select2" name="item_id[]">
 @foreach($item as $row)
   <option value="{{$row->id}}">{{$row->description}}</option>
 @endforeach	  
</select>

<?php } else if($type=='CUST') { ?>
<span>Customer:</span> <br/>
<select id="select2" multiple style="width:100%" class="form-control select2" name="customer_id[]">
 @foreach($customer as $row)
   <option value="{{$row->id}}">{{$row->master_name}}</option>
 @endforeach	    
</select>
<?php } else if($type=='GROUP') {
 ?>
<span>Group:</span> <br/>
<select id="multiselect6" multiple="multiple" class="form-control" name="id[]">
    <?php foreach($group as $row) { ?>
    <option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->description;?></option>
    <?php } ?>
</select>
<?php }else if($type=='SUBGROUP') {
 ?>
<span>Subgroup:</span> <br/>
<select id="multiselect7" multiple="multiple" class="form-control" name="id[]">
    <?php foreach($subGroup as $row) { ?>
    <option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->group_name;?></option>
    <?php } ?>
</select>
<?php }
 else if($type=='SALE') { ?>
<span>Salesman:</span> <br/>
<select id="multiselect4" multiple="multiple" class="form-control" name="salesman_id[]">
    <?php foreach($salesman as $row) { ?> 
    <option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->name;?></option>
    <?php } ?>
</select>
<?php } else if($type=='AREA') { ?>
<span>Area:</span> <br/>
<select id="multiselect5" multiple="multiple" class="form-control" name="id[]">
    <?php foreach($area as $row) { ?>
    <option value="<?php echo $row->id;?>" <?php //if($row->id==$locid) echo 'selected';?>><?php echo $row->name;?></option>
    <?php } ?>
</select>
<?php } ?>

<script>
"use strict";
$(document).ready(function () {
    ///$('#selcust').toggle();
    //$('#selitm').toggle();

    $("#select1").select2({
        theme: "bootstrap",
        placeholder: "Items"
    });

    
    $("#select2").select2({
        theme: "bootstrap",
        placeholder: "Customers"
    });
    
    $("#multiselect2").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    
    $("#multiselect3").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });

    $("#multiselect4").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect5").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect6").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
    $("#multiselect7").multiselect({
        enableFiltering: true,
        includeSelectAllOption: true,
        maxHeight: 300,
        dropUp: true
    });
});