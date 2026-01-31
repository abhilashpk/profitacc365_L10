<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Repositories\Parameter1\Parameter1Interface;
use App\Repositories\VatMaster\VatMasterInterface;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{ 
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $parameter1;
    protected $acsettings;
    protected $vat_master;
    protected $vatdata;
    protected $location;
    protected $company_data;

    public function __construct(
    Parameter1Interface $parameter1,
    VatMasterInterface $vat_master
){ 
    // Skip initialization on login/logout/auth routes
    if (request()->routeIs('login') ||
        request()->routeIs('login.submit') ||
        request()->routeIs('logout')) {
        return;
    }

    // Or skip based on path
    if (request()->is('login') ||
        request()->is('login/*') ||
        request()->is('logout') ||      // GET logout
        request()->is('logout/*') ||
        request()->is('auth/*')) {

        return;
    }

    $this->parameter1 = $parameter1;
    $this->vat_master = $vat_master;

    // Application settings
    $this->acsettings = $this->parameter1->getParameter1();
    $this->vatdata    = $this->vat_master->getActiveVatMaster();

    // Company session
    $this->company_data = DB::table('company')->first();

    if ($this->company_data) {
        Session::put('company',  $this->company_data->company_name);
        Session::put('city',     $this->company_data->city);
        Session::put('state',    $this->company_data->state);
        Session::put('country',  $this->company_data->country);
        Session::put('address',  $this->company_data->address);
        Session::put('pin',      $this->company_data->pin);
        Session::put('phone',    $this->company_data->phone);
        Session::put('vatno',    $this->company_data->vat_no);
        Session::put('logo',     $this->company_data->logo);
        Session::put('email',    $this->company_data->email);
    }

    // Default location
    $location = DB::table('location')
        ->where('is_default', 1)
        ->where('status', 1)
        ->whereNull('deleted_at')
        ->select('id', 'code', 'name')
        ->first();

    if ($location) {
        Session::put('location', $location->name);
        Session::put('location_id', $location->id);
    }

    // Module flags
    $resPara2 = DB::table('parameter2')
        ->whereIn('id', [4,10,17,24,35,36,37,42])
        ->where('status', 1)
        ->select('is_active', 'keyname')
        ->orderBy('id', 'ASC')
        ->get();

    foreach ($resPara2 as $row) {
        Session::put($row->keyname, $row->is_active);
    }

    // Trip entry
    Session::put('trip_entry', $this->acsettings->trip_entry ?? 0);
}

}

