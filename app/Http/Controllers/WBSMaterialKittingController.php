<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\MaterialIssuanceDetails;

class WBSMaterialKittingController extends Controller
{
    //

    public function get_wbs_kitting_details(Request $request) {
    	$kitting_details = MaterialIssuanceDetails::select('item', 'item_desc')->distinct('item')->get();

    	return response()->json(['kitting_details' => $kitting_details]);
    }

    public function get_wbs_kitting_details_by_po_no(Request $request) {
    	$kitting_details = MaterialIssuanceDetails::select('id', 'item', 'item_desc', 'usage', 'issued_qty')->distinct('item')
    	->where('po', $request->po_no)
    	->get();

    	return response()->json(['kitting_details' => $kitting_details]);
    }
}
