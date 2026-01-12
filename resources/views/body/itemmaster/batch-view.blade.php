<table class="table horizontal_table" id="batchTable">
    <thead>
    <tr>
        <th>Batch No</th>
        <th>Mfg. Date</th>
        <th>Exp. Date</th>
        <th>Qty. <input type="hidden" id="row_no" value="{{$no}}"> <input type="hidden" id="bth_count" value="{{count($batch)}}"></th>
        <th><button class="btn btn-success btn-xs funAddBacthRow" data-id="1" data-no="1"><i class="fa fa-fw fa-plus-circle"></i></button>@if($act=='edit')<input type="hidden" size="2" id="remId" value="{{$rem}}">@endif</th>
    </tr>
    </thead>
    <tbody>@php $i=0; @endphp
    @foreach($batch as $key => $val)
    @php $i++; @endphp
    <tr>
        <td class="btno"><input type="text" size="10" id="bthno_{{$i}}" class="bno" name="batch_no" value="{{$val}}" autocomplete="off">@if($act=='edit')<input type="hidden" size="2" id="bthid_{{$i}}" class="bid" name="batch_id" value="{{isset($ids[$key])?$ids[$key]:''}}">@endif</td>
        <td class="mfdt"><input type="text" size="12" id="bthmfg_{{$i}}" name="mfg_date" data-language='en' class="mfg-date" value="{{$mdate[$key]}}" readonly autocomplete="off"></td>
        <td class="exdt"><input type="text" size="12" id="bthexp_{{$i}}" name="exp_date" data-language='en' class="exp-date" value="{{$edate[$key]}}" readonly autocomplete="off"></td>
        <td class="bqty"><input type="text" size="8" id="bthqty_{{$i}}" name="qty" class="bth-qty" value="{{$qty[$key]}}" autocomplete="off"></td>
        <td class="del">@if($i > 1)<button class="btn btn-danger btn-xs funRemove" data-id="{{$i}}" data-no="{{$i}}"><i class="fa fa-fw fa-times-circle"></i></button>@endif</td> 
    </tr>
    @endforeach
    </tbody>
</table>
<script>
@if($act=='edit')
   $('.mfg-date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy'} );
   $('.exp-date').datepicker( { autoClose: true,dateFormat: 'dd-mm-yyyy'} );
@endif
</script>