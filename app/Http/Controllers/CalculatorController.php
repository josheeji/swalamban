<?php

namespace App\Http\Controllers;

use App\Models\BrokerCommission;
use App\Models\BrokerCommissionType;
use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    protected $DPFee = 25;

    public function index()
    {
        return view('calculator.sip');
    }

    public function sip(Request $request)
    {
        return view('calculator.sip');
    }

    public function calculateSip(Request $request)
    {
        $monthlyInvestment = 0;
        $periodInYears = 0;
        $periodInMonths = 0;
        $estimatedReturnRate = 0;
        $monthlyRateOfReturn = 0;
        $totalInvestment = 0;
        $maturity = 0;
        $estimatedReturn = 0;

        $monthlyInvestment = $request->post('monthly_investment');
        $periodInYears = $request->post('period_in_years');
        $periodInMonths = $request->post('period_in_months');
        $estimatedReturnRate = $request->post('estimated_return_rate');
        $monthlyRateOfReturn = $request->post('monthly_rate_of_return');
        $totalInvestment = $request->post('total_investment');

        $calc = 0;
        $calc = 1 + $monthlyRateOfReturn;
        $calc = pow($calc, $periodInMonths) - 1;
        $calc = ($monthlyInvestment * $calc) / $monthlyRateOfReturn;
        $calc = $calc * (1 + $monthlyRateOfReturn);
        $maturity = round($calc, 4);
        $estimatedReturn = round($maturity - $totalInvestment, 2);

        return json_encode(['maturity' => $maturity, 'estimatedReturn' => $estimatedReturn]);
    }

    public function buySell()
    {
        return view('calculator.buy-sell');
    }

    public function calculateBuySell(Request $request)
    {
        $type = $request->post('type');
        switch ($type) {
            case 'buy':
                $result = $this->calculateBuy($request);
                break;
            case 'sell':
                $result = $this->calculateSell($request);
                break;
        }

        return view('calculator.buysell-result', ['result' => $result, 'type' => $type]);
    }

    public function rightShare()
    {
        return view('calculator.right-share');
    }

    public function bonusShare()
    {
        return view('calculator.bonus-share');
    }

    protected function calculateBuy($request)
    {
        $noOfUnits = $request->post('no_of_units');
        $buyingPrice = $request->post('buying_price');

        $transactionValue = $noOfUnits * $buyingPrice;
        $SEBONFee = $transactionValue * (0.015 / 100);
        $DPFee = $this->DPFee;
        $brokerCommission = $this->brokerCommissionBuy($transactionValue);
        $totalBuyingCost = $brokerCommission + $DPFee + $SEBONFee + $transactionValue;
        $avgCostPerShare = $totalBuyingCost / $noOfUnits;
        return [
            'noOfUnits' => $noOfUnits,
            'buyingPrice' => $this->numberFormat($buyingPrice),
            'transactionValue' => $this->numberFormat($transactionValue),
            'SEBONFee' => $this->numberFormat($SEBONFee),
            'DPFee' => $this->numberFormat($DPFee),
            'brokerCommission' => $this->numberFormat($brokerCommission),
            'totalBuyingCost' => $this->numberFormat($totalBuyingCost),
            'avgCostPerShare' => $this->numberFormat($avgCostPerShare)
        ];
    }

    protected function calculateSell($request)
    {
        $noOfUnits = $request->post('no_of_units');
        $buyingPrice = $request->post('buying_price');
        $sellingPrice = $request->post('selling_price');

        $transactionValue = $noOfUnits * $sellingPrice;
        $SEBONFee = $transactionValue * (0.015 / 100);
        $DPFee = $this->DPFee;
        $brokerCommission = $this->brokerCommissionBuy($transactionValue);
        $CGT = 0;
        if (($sellingPrice - $buyingPrice) > 0) {
            $CGT = $this->investorType($request->post('investor_type'));
            $CGT = ($CGT * (($sellingPrice - $buyingPrice) * $noOfUnits - $SEBONFee - $DPFee - $brokerCommission));
        }
        $totalReceivableAmount = $transactionValue - $SEBONFee - $DPFee - $brokerCommission - $CGT;

        return [
            'noOfUnits' => $noOfUnits,
            'buyingPrice' => $this->numberFormat($buyingPrice),
            'transactionValue' => $this->numberFormat($transactionValue),
            'SEBONFee' => $this->numberFormat($SEBONFee),
            'DPFee' => $this->numberFormat($DPFee),
            'brokerCommission' => $this->numberFormat($brokerCommission),
            'CGT' => $this->numberFormat($CGT),
            'totalReceivableAmount' => $this->numberFormat($totalReceivableAmount)
        ];
    }

    protected function numberFormat($number)
    {
        return number_format($number, 2, '.', ',');
    }

    protected function brokerCommissionBuy($transactionValue)
    {
        if ($transactionValue <= 50000) {
            $commission = BrokerCommission::where('range_to', '50000')->first();
        } else if ($transactionValue > 50000 && $transactionValue <= 500000) {
            $commission = BrokerCommission::where('range_to', '500000')->first();
        } else if (500000 > $transactionValue && $transactionValue <= 2000000) {
            $commission = BrokerCommission::where('range_to', '2000000')->first();
        } else if (2000000 > $transactionValue && $transactionValue <= 10000000) {
            $commission = BrokerCommission::where('range_to', '10000000')->first();
        } else {
            $commission = BrokerCommission::where('range_to', '50000000000')->first();
        }
        return ($commission->commission / 100) * $transactionValue;
    }

    protected function investorType($type)
    {
        switch ($type) {
            case 1:
                $commission = BrokerCommissionType::where('type', 'Individual')->first();
                break;
            case 2:
                $commission = BrokerCommissionType::where('type', 'Institution')->first();
                break;
        }
        return $commission->commission;
    }
}
