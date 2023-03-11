<table>
    <tr>
        <td colspan="4"><strong>Leads - {{ date('d M, Y') }}</strong></td>
    </tr>
</table>
<table class="table datatable-column-search-inputs defaultTable">
    <thead>
        <tr>
            <th><strong>S.No.</strong></th>
            <th><strong>Type</strong></th>
            <th><strong>Full Name</strong></th>
            <th><strong>Email</strong></th>
            <th><strong>Contact No.</strong></th>
        </tr>
    </thead>
    <tbody>
        @foreach($accountTypes as $index=>$accountType)
            @if($accountType->leads()->count())
                @foreach($accountType->leads as $key => $lead)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $accountType->title }}</td>
                        <td>{{ $lead->full_name }}</td>
                        <td>{{ $lead->email }}</td>
                        <td>{{ $lead->contact_no }}</td>
                    </tr>
                @endforeach
            @endif
        @endforeach
    </tbody>
</table>