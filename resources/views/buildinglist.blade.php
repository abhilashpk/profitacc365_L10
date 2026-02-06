<table class="table table-striped">
    <thead>
            <tr>
                <th>Building Code</th>
                <th>Building Name</th>
                <th></th>
            </tr>
        </thead>
            <tbody>
        
            @foreach($result as $row)
            <tr>
                <td>{{ $row->buildingcode }}</td>
                <td>{{ $row->buildingname }}</td>
                <td><a href="#" class="btn btn-info view-cont" data-bid="{{$row->id}}">View</a></td>
            </tr>
            @endforeach
            </tbody>
        </tbody>
    </table>