<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\Guard\NfcTagLogs;
use App\Models\Guard\NfcTags;
use Illuminate\Http\Request;

class  NfcTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = NfcTagLogs::with(["device", "guarduser"]);

        $model->where("company_id", $request->company_id);

        $model->when($request->filled("user_id"), function ($q) use ($request) {
            $q->where("user_id", $request->user_id);
        });


        return   $model->orderByDesc("log_time")->paginate($request->per_page ?? 10);
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
     * @param  \App\Models\Guard\NfcTags  $nfcTags
     * @return \Illuminate\Http\Response
     */
    public function show(NfcTags $nfcTags)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Guard\NfcTags  $nfcTags
     * @return \Illuminate\Http\Response
     */
    public function edit(NfcTags $nfcTags)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guard\NfcTags  $nfcTags
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NfcTags $nfcTags)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guard\NfcTags  $nfcTags
     * @return \Illuminate\Http\Response
     */
    public function destroy(NfcTags $nfcTags)
    {
        //
    }

    public function syncGuardLogs(Request $request)
    {


        $request->validate([
            "company_id" => "required",
            "log_time" => "required",
            "user_id" => "required",
            "serial_number" => "required",
        ]);

        $data =  $request->all();;

        unset($data["sync_status"]);
        unset($data["id"]);
        unset($data["nfc_location"]);


        $record = NfcTagLogs::where("serial_number", $request->serial_number)
            ->where("user_id", $request->user_id)
            ->where("log_time", $request->log_time)->get();


        if (count($record) == 0)
            $return = NfcTagLogs::create($data);

        return $this->response("Log Created Successfully", $data, true);


        if ($return)
            return $this->response("Log Created Successfully", $data, true);
        else return $this->response("Error", $data, false);
    }
}
