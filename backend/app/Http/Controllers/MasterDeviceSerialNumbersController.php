<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\MasterDeviceSerialNumbers;
use Illuminate\Http\Request;

class MasterDeviceSerialNumbersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = Device::with(["company"]);

        $model->where("serial_number", "!=", "Manual");
        $model->where("serial_number", "!=", "Mobile");

        $model->when($request->filled("common_search"), function ($q) use ($request) {
            $q->where(function ($qq) use ($request) {

                $qq->where("serial_number", "ILIKE", "%$request->common_search%")
                    ->orWhere("model_number", "ILIKE", "%$request->common_search%")
                    ->orWhere("device_type", "ILIKE", "%$request->common_search%")
                    ->orWhereHas("company", function ($qqq) use ($request) {
                        $qqq->where("name", "ILIKE", "%$request->common_search%");
                    });
            });
        });

        $model->orderBy("serial_number", "asc");

        return  $model->paginate($request->perpage ?? 10);
    }
    // public function index_old(Request $request)
    // {
    //     $model = MasterDeviceSerialNumbers::with(["assignedcompany", "liveDevice"]);


    //     $model->when($request->filled("common_search"), function ($q) use ($request) {
    //         $q->where(function ($qq) use ($request) {

    //             $qq->where("master_serial_number", "ILIKE", "%$request->common_search%")
    //                 ->orWhere("device_model", "ILIKE", "%$request->common_search%")
    //                 ->orWhere("device_type", "ILIKE", "%$request->common_search%")
    //                 ->orWhereHas("assignedcompany", function ($qqq) use ($request) {
    //                     $qqq->where("name", "ILIKE", "%$request->common_search%");
    //                 });
    //         });
    //     });

    //     $model->orderBy("master_serial_number", "asc");

    //     return  $model->paginate($request->perpage ?? 10);
    // }

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
        $request->validate([
            'company_id' => 'nullable',
            'device_type' => 'nullable',
            'serial_number' => 'required',
            'model_number' => 'required',
            'device_description' => 'nullable',
            'picture' => 'nullable',
        ]);


        $data = $request->all();
        $data["assigned_datetime"] = date("Y-m-d H:i:s");
        $data["status_id"] = 1;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/master_devices'), $fileName);
            $data['picture'] = $fileName;
        }
        $data["device_id"] = $request->serial_number;
        $data["status_id"] = 2;
        unset($data["attachment"]);
        unset($data["editId"]);

        $isExist = Device::where('serial_number', $request->serial_number)->first();


        if ($request->filled("editId")) {


            if ($isExist === null || $request->editId == $isExist->id) {
                $record = Device::where("id", $request->editId)->update($data);
                return $this->response(' Serial Number is Updated Successfully', $data, true);
            } else {
                return ["status" => false, "errors" => ["serial_number" => ["Serial Number is already in use."]]];
            }
        } else {
            if ($isExist === null) {
                $record = Device::create($data);
                return $this->response('New Serial Number is created Successfully.', $record, true);
            } else {
                return ["status" => false, "errors" => ["serial_number" => ["Serial Number is already in use."]]];
            }
        }

        return ["status" => false, "errors" => ["serial_number" => ["No Updates"]]];
        //
    }
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'company_id' => 'nullable',
    //         'device_type' => 'nullable',
    //         'master_serial_number' => 'required',
    //         'device_model' => 'required',
    //         'device_description' => 'nullable',
    //         'picture' => 'nullable',
    //     ]);


    //     $data = $request->all();
    //     $data["assigned_datetime"] = date("Y-m-d H:i:s");


    //     if ($request->hasFile('attachment')) {
    //         $file = $request->file('attachment');
    //         $fileName = time() . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('/master_devices'), $fileName);
    //         $data['picture'] = $fileName;
    //     }
    //     unset($data["attachment"]);
    //     unset($data["editId"]);

    //     $isExist = MasterDeviceSerialNumbers::where('master_serial_number', $request->master_serial_number)->first();


    //     if ($request->filled("editId")) {


    //         if ($isExist === null || $request->editId == $isExist->id) {
    //             $record = MasterDeviceSerialNumbers::where("id", $request->editId)->update($data);
    //             return $this->response(' Serial Number is Updated Successfully', $data, true);
    //         } else {
    //             return ["status" => false, "errors" => ["master_serial_number" => ["Serial Number is already in use."]]];
    //         }
    //     } else {
    //         if ($isExist === null) {
    //             $record = MasterDeviceSerialNumbers::create($data);
    //             return $this->response('New Serial Number is created Successfully.', $record, true);
    //         } else {
    //             return ["status" => false, "errors" => ["master_serial_number" => ["Serial Number is already in use."]]];
    //         }
    //     }

    //     return ["status" => false, "errors" => ["master_serial_number" => ["No Updates"]]];
    //     //
    // }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterDeviceSerialNumbers  $masterDeviceSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function show(MasterDeviceSerialNumbers $masterDeviceSerialNumbers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterDeviceSerialNumbers  $masterDeviceSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function edit(MasterDeviceSerialNumbers $masterDeviceSerialNumbers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterDeviceSerialNumbers  $masterDeviceSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MasterDeviceSerialNumbers $masterDeviceSerialNumbers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterDeviceSerialNumbers  $masterDeviceSerialNumbers
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $record = Device::where('id', $request->id)->delete();
        if ($record) {

            return $this->response('Serial Number is  Successfully deleted.', $record, true);
        } else {
            return $this->response('Serial Number can not delete.', null, false);
        }
    }
}
