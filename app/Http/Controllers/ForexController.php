<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Repositories\ForexOrderRepository;
use App\Repositories\ForexRepository;
use Illuminate\Http\Request;

class ForexController extends Controller
{

    protected $forex;

    public function __construct(
        ForexRepository $forex,
        ForexOrderRepository $forexOrder
    ) {
        $this->forex = $forex;
        $this->forexOrder = $forexOrder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $forex = $this->forex->all()->toArray();
        $data['currency'] = $this->forex->all()->toArray();

        $forexNamesWithOrder = $this->forexOrder->orderBy('order', 'asc')->get()->pluck('name', 'code')->toArray();

        // dd($data['forex']);
        $date = date('Y-m-d 10:00');
        if (!empty($request->date))
            $date = $request->date . " 10:00";
        else if ($forex)
            $date = $forex[0]['created_at'];
        $data['date'] = Helper::formatDate($date, 5);
        // dd($data['forex']);
        if (!empty($request->currency) || !empty($request->date)) {
            // if (!empty($request->date)) {
            //     $formatted = strtoupper(date('d-M-y', strtotime($request->date)));
            // } else
            //     $formatted = "";
            // // dd($formatted);
            // $old_data = $this->oracleconn($request->currency, $formatted);
            // $forex = $old_data;
        }

        $forex_codes = [];
        foreach ($forex as $item) {
            $item['forex_name'] = $forexNamesWithOrder[$item['FXD_CRNCY_CODE']]; //pushing forex name to $item array
            $forex_codes[$item['FXD_CRNCY_CODE']] = $item;
        }

        $order = array_keys($forexNamesWithOrder); // order of forex codes

        //Sorting $forex_code according the order of $order
        uksort($forex_codes, function ($key1, $key2) use ($order) {
            return (array_search($key1, $order) > array_search($key2, $order));
        });

        $data['forex'] = $forex_codes;

        $date = $request->has('date') ? $request->get('date') : date('Y-m-d');
        $data['date'] = Helper::formatDate($date, 7);
        $forexes = $this->forex->where('RTLIST_DATE', $date);
        if ($request->has('currency') && $request->get('currency') != null) {
            $forexes->where('FXD_CRNCY_CODE', $request->get('currency'));
        }
        $data['forexes'] = $forexes->get();

        return view('forex.index', $data);
    }

    public function save()
    {
        $this->forex->model()->truncate();

        $db =
            "(DESCRIPTION =
                   (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.178.203)(PORT = 1521))
                   (CONNECT_DATA =
                     (SERVER = DEDICATED)
                     (SERVICE_NAME = orcl)
                   )
                 )";

        $conn = oci_connect("DELCHNL", "DelChnlkbl", $db);
        if ($conn) {
            $stid = oci_parse($conn, "SELECT * from VW_FOREX");
            oci_execute($stid);
            $created_at = date('Y-m-d H:i:s');
            while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                // $data = [];
                // Use the uppercase column names for the associative array indices
                $row['created_at'] = $created_at;
                if ($row['VAR_CRNCY_CODE'] == 'NPR')
                    $this->forex->create($row);
            }



            oci_free_statement($stid);
            oci_close($conn);
        } else {
            echo 'Connection Error';
            die;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function oracleconn($currency = "", $date = "")
    {
        $db =
            "(DESCRIPTION =
               (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.178.203)(PORT = 1521))
               (CONNECT_DATA =
                 (SERVER = DEDICATED)
                 (SERVICE_NAME = orcl)
               )
             )";

        $conn = oci_connect("DELCHNL", "DelChnlkbl", $db);
        if ($conn) {
            $where = " WHERE VAR_CRNCY_CODE='NPR'";
            if ($currency) {
                $where .= " AND FXD_CRNCY_CODE='$currency'";
            }

            if ($date) {
                $where .= " AND RTLIST_DATE='$date'";
            }
            $stid = oci_parse($conn, "SELECT * from VW_FOREX_ALL" . $where);
            oci_execute($stid);
            // $forex = oci_fetch_array($stid,OCI_ASSOC+OCI_RETURN_NULLS);
            // dd($forex);
            $old_forex = [];
            while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
                // $data = [];
                // Use the uppercase column names for the associative array indices
                // print_r($row);
                // echo "<br />";
                if ($row['VAR_CRNCY_CODE'] == 'NPR')
                    $old_forex[] = $row;
            }

            // echo "SELECT * from VW_FOREX_ALL".$where;


            oci_free_statement($stid);
            oci_close($conn);
            return $old_forex;
        } else {
            echo 'Connection Error';
            die;
        }
    }
}
