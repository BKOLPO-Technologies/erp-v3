<?php

namespace App\Http\Controllers\Accounts;

use App\Models\Accounts\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Chart of account';

        return view('Accounts.voucher.chart_of_account.index',compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Create chart of account';
        $companies = Company::where('status',1)->latest()->get();

        return view('Accounts.voucher.chart_of_account.create',compact('pageTitle', 'companies'));
    }
}
