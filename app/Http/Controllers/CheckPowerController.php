<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devices;

class CheckPowerController extends Controller
{
    //
    public function index()
    {
        return response()->json(
            Devices::all()->append('has_power')
        );
    }
}
