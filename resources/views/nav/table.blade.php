<div class="table-responsive">
    <table class="tablefull">
        <tr>
            <th>English Date</th>
            <th>Nepali Date</th>
            <th>NAV</th>
        </tr>
        @if(isset($data) && !$data->isEmpty())
        @foreach($data as $nav)
        <tr>
            <td>{{ Helper::formatDate($nav->publish_at) }}</td>
            <td>
                @php
                $nepDateApi = new \NepaliDateApi();
                $nepaliDate = $nepDateApi->eng_to_nep(date('Y',strtotime($nav->publish_at)),date('m',strtotime($nav->publish_at)),date('d',strtotime($nav->publish_at)), true);
                if(isset($nepaliDate))
                echo $nepaliDate['date'] . ' ' . $nepaliDate['nmonth'] . ', ' . $nepaliDate['year'];
                @endphp
            </td>
            <td>{{ $nav->value }}</td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="3">No record found.</td>
        </tr>
        @endif
    </table>
</div>