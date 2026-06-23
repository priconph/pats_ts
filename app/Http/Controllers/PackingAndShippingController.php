<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\ShipmentConfirmation;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Auth;


class PackingAndShippingController extends Controller
{
	public function fn_packingandshipping(Request $request)
	{
		return view('packingandshipping');
	}

}
