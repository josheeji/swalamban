<div class="table-responsive">
    <table class="table table-stripped" id="card-table">
        <thead>
            <tr>
                <th valign="top" width="35%">Full Name </th>
                <th valign="top">Fathers Name</th>
                <th valign="top">Shareholder No.</th>
                <th valign="top">Share holder/BOID</th>
                <th valign="top">Issue Rights</th>
                <th valign="top">Total Kitta</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @if(isset($bonus))
                <td valign="top" style="height:50px;"><strong>{!! isset($bonus) && !empty($bonus) ? $bonus->name : '' !!}</strong></td>
                <td valign="top" style="height:50px;"><strong>{!! isset($bonus) && !empty($bonus) ? $bonus->fathers_name : '' !!}</strong></td>
                <td valign="top"><strong>{!! isset($bonus) && !empty($bonus) ? $bonus->shareholder_no : '' !!}</strong></td>
                <td valign="top"><strong>{!! isset($bonus) && !empty($bonus) ? $bonus->boid : '' !!}</strong></td>
                <td valign="top"><strong>{!! isset($bonus) && !empty($bonus) ? $bonus->actual_bonus : '' !!}</strong></td>
                <td valign="top"><strong>{!! isset($bonus) && !empty($bonus) ? $bonus->total : '' !!}</strong></td>
                @else
                <td colspan="6">No records found.</td>
                @endif
            </tr>
        </tbody>
    </table>
</div>