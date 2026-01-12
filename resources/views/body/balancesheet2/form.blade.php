<form action="{{ route('balancesheet2.report') }}" method="POST">
    {{ csrf_field() }}
    <label>Start Date</label>
    <input type="date" name="start_date" >
    
    <label>End Date</label>
    <input type="date" name="end_date" >

    <label>Report Type</label>
    <select name="report_type">
        <option value="summary">Summary</option>
        <option value="detail">Detail</option>
    </select>

    <label><input type="checkbox" name="include_opening"> Include Opening Balance</label>

    <button type="submit">Generate</button>
    <button type="submit" name="export_excel" value="1">Export to Excel</button>
</form>
