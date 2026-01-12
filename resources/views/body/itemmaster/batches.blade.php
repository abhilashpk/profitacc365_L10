<table class="table horizontal_table" id="batchTable">
    <thead>
    <tr>
        <th>Batch No</th>
        <th>Mfg. Date</th>
        <th>Exp. Date</th>
        <th>Qty.</th>
    </tr>
    </thead>
    <tbody>
    @foreach($batches as $key => $item)
    <tr>
        <td class="btno">{{$item->batch_no}}</td>
        <td class="mfdt">{{date('d-m-Y',strtotime($item->mfg_date))}}</td>
        <td class="exdt">{{date('d-m-Y',strtotime($item->exp_date))}}</td>
        <td class="bqty">{{$item->quantity}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
