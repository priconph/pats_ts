<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\DrawingNo;
use App\Model\ACDCSDrawingNo;


class DrawingNoController extends Controller
{

    public function get_drawing_no(Request $request){
        $device_name = explode('-', $request['device_name']);
        $device_name_str = $device_name[0].'-'.$device_name[1];
        // $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    ->where(function($query) use ($device_name_str, $request_str) {
                        $query->where('document_no', "like",'%'.$device_name_str.'%')
                        ->orWhere('document_no', "like",'%'.$request_str.'%');
                    })
                    ->limit('200')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_adrawing_no(Request $request){
        $device_name = explode('-', $request['device_name']);
        $device_name_str = $device_name[0].'-'.$device_name[1];
        // $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    ->where(function($query) use ($device_name_str, $request_str) {
                        $query->where('document_no', "like",'%'.$device_name_str.'%')
                        ->orWhere('document_no', "like",'%'.$request_str.'%');
                    })
                    ->where(function($query) {
                        $query->where('document_code', 'A Drawing')
                        ->where('document_code', 'AA Drawing');
                    })
                    ->limit('300')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_gdrawing_no(Request $request){
        $device_name = explode('-', $request['device_name']);
        $device_name_str = $device_name[0].'-'.$device_name[1];
        // $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    ->where(function($query) use ($device_name_str, $request_str) {
                        $query->where('document_no', "like",'%'.$device_name_str.'%')
                        ->orWhere('document_no', "like",'%'.$request_str.'%');
                    })
                    ->where(function($query) {
                        $query->where('document_code', 'G Drawing')
                        ->where('document_code', 'AG Drawing');
                    })
                    ->limit('300')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_odrawing_no(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){ 
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    // ->where('doc_title', "like",'%'.$device_name_str.'%')
                    // ->Orwhere('doc_title', "like",'%'.$request_str.'%')
                    ->where(function($query) use ($device_name_str, $request_str) {
                        $query->where('document_no', "like",'%'.$device_name_str.'%')
                        ->orWhere('document_no', "like",'%'.$request_str.'%');
                    })
                    ->where(function($query) {
                        $query->where('document_code', 'KL Drawing')
                        ->where('document_code', 'AC Drawing');
                    })
                    // ->where('doc_type', 'KL Drawing')
                    ->limit('300')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_WIDoc(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    // ->where(function($query) use ($device_name_str, $request_str) {
                    //     $query->where('document_no', "like",'%'.$device_name_str.'%')
                    //     ->orWhere('document_no', "like",'%'.$request_str.'%');
                    // })
                    ->where('document_code', 'WI')
                    ->where('station', 'like', '%Final Visual%')
                    // ->where('document_code', 'Work Instruction')
                    ->limit('300')
                    ->get();
                    // ->toSql();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_OGM_VIG_IGDoc(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    ->where(function($query) use ($device_name_str, $request_str) {
                        $query->where('document_no', "like",'%'.$device_name_str.'%')
                        ->orWhere('document_no', "like",'%'.$request_str.'%');
                    })
                    ->where(function($query) {
                        $query->where('document_code', 'OGM')
                        ->orWhere('document_code', 'IG')
                        ->orWhere('document_code', 'VIG');
                    })
                    // ->where('station', 'like', '%Final Visual%')
                    ->limit('300')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_PPDoc(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        // if( strlen($request_str)<3 ){
        //     $request_str = 'show nothing';
        // }
        $DrawingNo = DrawingNo::where('logdel', 0)
                    ->where(function($query) use ($device_name_str, $request_str) {
                        $query->where('document_no', "like",'%'.$device_name_str.'%')
                        ->orWhere('document_no', "like",'%'.$request_str.'%');
                    })
                    ->where('document_code', 'PP')
                    ->where('station', 'like', '%Final Visual%')
                    ->limit('300')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_UDDoc(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        // $device_name_str = $device_name[0];
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        // $DrawingNo = DrawingNo::where('logdel', 0)
        $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
                    // ->where(function($query) use ($device_name_str, $request_str) {
                    //     $query->where('doc_title', "like",'%'.$device_name_str.'%')
                    //     ->orWhere('doc_title', "like",'%'.$request_str.'%');
                    // })
                    ->where('doc_type', 'Urgent Direction')
                    ->where('originator_code', 'TS')
                    // ->where('station', 'like', '%Final Visual%')
                    ->limit('200')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_PackDocdrawing(Request $request){
        $device_name = explode('-', $request['device_name']);
        // $device_name_str = $device_name[0].'-'.$device_name[1];
        $device_name_str = $device_name[0];
        // $device_name_str = 'Packaging';
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
                    // ->where(function($query) use ($device_name_str, $request_str) {
                    //     $query->where('doc_title', "like",'%'.$device_name_str.'%')
                    //     ->orWhere('doc_title', "like",'%'.$request_str.'%');
                    // })
                    // ->where('doc_title', "like",'%'.$device_name_str.'%')
                    ->where('model', "like",'%'.$device_name_str.'%')
                    ->orWhere('series', "like",'%'.$device_name_str.'%')
                    ->orWhere('model', "like",'%BGA%')
                    ->where('originator_code', 'TS')
                    ->where('doc_type', 'J Drawing')
                    ->orWhere('doc_type', 'R Drawing')
                    // ->where('station', 'like', '%Final Visual%')
                    // ->limit('200')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_PackSpecsdrawing(Request $request){
        $device_name = explode('-', $request['device_name']);
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
                    ->where('model', "like",'%'.$device_name_str.'%')
                    // ->orWhere('model', "like",'%BGA%')
                    ->where('originator_code', 'TS')
                    ->orWhere('originator_code', 'TS MFG-PRODN')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_OTUDdrawing(Request $request){
        $device_name = explode('-', $request['device_name']);
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
                    ->where('model', "like",'%'.$device_name_str.'%')
                    ->orWhere('series', "like",'%'.$device_name_str.'%')
                    // ->orWhere('model', "like",'%BGA%')
                    ->where('originator_code', 'TS')
                    ->orWhere('originator_code', 'PPS')
                    ->where('doc_type', 'Urgent Direction')
                    ->orWhere('doc_type', 'Special Instruction')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }

    public function get_WIDdrawing(Request $request){
        $device_name = explode('-', $request['device_name']);
        $device_name_str = $device_name[0];
        $request_str = $request->str;
        if( strlen($request_str)<3 ){
            $request_str = 'show nothing';
        }
        $DrawingNo = ACDCSDrawingNo::where('logdel', 0)
                    // ->where('model', "like",'%'.$device_name_str.'%')
                    // ->orWhere('model', "like",'%BGA%')
                    ->where('originator_code', "like",'%TS%')
                    ->where('doc_type', 'Work Instruction')
                    ->get();
        $doc = array();
        $doc['doc'] = $DrawingNo;

        return ($doc);
    }


   


}
