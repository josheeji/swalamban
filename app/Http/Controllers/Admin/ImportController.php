<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AtmImport;
use App\Imports\BranchImport;
use App\Imports\DownloadImport;
use App\Imports\FinancialReportImport;
use App\Imports\RemitKumariImport;
use Illuminate\Http\Request;
use Excel;

class ImportController extends Controller
{
    public function importAtm()
    {
        return view('admin.import.import-atm');
    }

    public function storeAtm(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048|mimes:xls,xlsx',
        ]);
        // $path = $request->file('file')->getRealPath();
        $path1 = $request->file('file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        Excel::import(new AtmImport, $request->file('file'));
        return redirect()->route('admin.import.import-atm')
            ->with('flash_notice', 'ATM location imported successfully.');
    }

    public function importBranch()
    {
        return view('admin.import.import-branch');
    }

    public function storeBranch(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048|mimes:xls,xlsx',
        ]);
        // $path = $request->file('file')->getRealPath();

        // $path1 = $request->file('file')->store('temp');
        // $path = storage_path('app') . '/' . $path1;
        $test = Excel::import(new BranchImport, $request->file('file'));
        return redirect()->route('admin.import.import-branch')
            ->with('flash_notice', 'Branch imported successfully.');
    }

    public function importDownload()
    {
        return view('admin.import.import-download');
    }

    public function storeDownload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048|mimes:xls,xlsx',
        ]);
        $path = $request->file('file')->getRealPath();

        Excel::import(new DownloadImport, $path);

        return redirect()->route('admin.import.import-download')
            ->with('flash_notice', 'Download imported successfully.');
    }

    public function importFinancialReport()
    {
        return view('admin.import.import-financial-report');
    }

    public function storeFinancialReport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048|mimes:xls,xlsx',
        ]);
        $path = $request->file('file')->getRealPath();
        Excel::import(new FinancialReportImport, $path);

        return redirect()->route('admin.import.import-financial-report')
            ->with('flash_notice', 'Financial report imported successfully.');
    }

    public function importRemitKumari()
    {
        return view('admin.import.import-remit-kumari');
    }

    public function storeRemitKumari(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048|mimes:xls,xlsx',
        ]);
        $path = $request->file('file')->getRealPath();
        Excel::import(new RemitKumariImport, $path);

        dd('end');

        return redirect()->route('admin.import-import-remit-kumari')
            ->with('flash_notice', ['Remit Kumari paying imported successfully.']);
    }
}
