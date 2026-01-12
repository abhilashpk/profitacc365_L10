<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function showReport()
    {
        return view('body.reports'); // resources/views/reports.blade.php
    }
}

