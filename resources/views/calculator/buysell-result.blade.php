<p>Share calculator output data</p>
<table class="table table-stripped">
    @if($type == 'buy')
    <tr>
        <th>No. of Units</th>
        <td>{{ $result['noOfUnits'] }}</td>
    </tr>
    <tr>
        <th>Buying Price</th>
        <td>Rs {{ $result['buyingPrice'] }}</td>
    </tr>
    <tr>
        <th>Transaction Value</th>
        <td>Rs {{ $result['transactionValue'] }}</td>
    </tr>
    <tr>
        <th>SEBON Fee</th>
        <td>Rs {{ $result['SEBONFee'] }}</td>
    </tr>
    <tr>
        <th>DP Fee</th>
        <td>Rs {{ $result['DPFee'] }}</td>
    </tr>
    <tr>
        <th>Broker Commission</th>
        <td>Rs {{ $result['brokerCommission'] }}</td>
    </tr>
    <tr>
        <th>Total Buying Cost</th>
        <td>Rs {{ $result['totalBuyingCost'] }}</td>
    </tr>
    <tr>
        <th>Average Cost Per Share</th>
        <td>Rs {{ $result['avgCostPerShare'] }}</td>
    </tr>
    @endif
    @if($type == 'sell')
    <tr>
        <th>No. of Units</th>
        <td>{{ $result['noOfUnits'] }}</td>
    </tr>
    <tr>
        <th>Buying Price</th>
        <td>Rs {{ $result['buyingPrice'] }}</td>
    </tr>
    <tr>
        <th>Transaction Value</th>
        <td>Rs {{ $result['transactionValue'] }}</td>
    </tr>
    <tr>
        <th>SEBON Fee</th>
        <td>Rs {{ $result['SEBONFee'] }}</td>
    </tr>
    <tr>
        <th>DP Fee</th>
        <td>Rs {{ $result['DPFee'] }}</td>
    </tr>
    <tr>
        <th>Broker Commission</th>
        <td>Rs {{ $result['brokerCommission'] }}</td>
    </tr>
    <tr>
        <th>CGT</th>
        <td>Rs {{ $result['CGT'] }}</td>
    </tr>
    <tr>
        <th>Total Receivable Amount</th>
        <td>Rs {{ $result['totalReceivableAmount'] }}</td>
    </tr>
    @endif
</table>