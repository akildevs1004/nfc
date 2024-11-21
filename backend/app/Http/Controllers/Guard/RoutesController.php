<?php

namespace App\Http\Controllers\Guard;

use App\Http\Controllers\Controller;
use App\Models\Guard\RouteLocations;
use Illuminate\Http\Request;
use App\Models\Guard\Routes;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;

class RoutesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = Routes::with(["routeLocations.device"]);

        $model->where("company_id", $request->company_id);

        // $model->when($request->filled("user_id"), function ($q) use ($request) {
        //     $q->where("user_id", $request->user_id);
        // });


        return   $model->orderBy("name", "asc")->paginate($request->per_page ?? 10);
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
     * @param  \App\Models\Routes  $routes
     * @return \Illuminate\Http\Response
     */
    public function show(Routes $routes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Routes  $routes
     * @return \Illuminate\Http\Response
     */
    public function edit(Routes $routes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Routes  $routes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Routes $routes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Routes  $routes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Routes $routes)
    {
        //
    }

    public function updateRouteLocaitons(Request $request)
    {
        $request->validate([
            "company_id" => "required",
            "route_checkpoints" => "required",
            "route_name" => "required",
            "route_notes" => "required",
            "route_start_hour" => "required",
            "route_start_minute" => "required",
            "route_end_hour" => "required",
            "route_end_minute" => "required",
            "repeat_minutes" => "required",
        ]);
        if (count($request->route_checkpoints) == 0) {
            return $this->response("Minimum 1 Checkpoint is required", null, false);
        }

        if ($request->filled("route_id")) { //update 


            $isExist = Routes::where("name", $request->route_name)->where("id", "!=", $request->route_id)->count();
            if ($isExist) {
                return $this->response("Route Name is already exist", null, false);
            }

            $data = [

                "name" => $request->route_name,
                "start_time" => $request->route_start_hour . ':' . $request->route_start_minute . ":00",
                "end_time" => $request->route_end_hour . ':' . $request->route_end_minute . ":00",

                "notes" => $request->route_notes,
                "repeat_minutes" => $request->repeat_minutes,
                "sun" => $request->sun_checkbox,
                "mon" => $request->mon_checkbox,
                "tue" => $request->tue_checkbox,
                "wed" => $request->wed_checkbox,
                "thu" => $request->thu_checkbox,
                "fri" => $request->fri_checkbox,
                "sat" => $request->sat_checkbox,



            ];
            $routeId = Routes::where("id", $request->route_id)->update($data);



            if ($request->route_id) {
                RouteLocations::where("route_id", $request->route_id)->delete();
                foreach ($request->route_checkpoints as  $checkpoint) {
                    if ($checkpoint['device_id'] != '') {

                        $data = [
                            "company_id" => $request->company_id,
                            "route_id" => $request->route_id,
                            // "schedule_time" => $checkpoint['start_hour'] . ':' . $checkpoint['start_minute'] . ":00",
                            "device_id" => $checkpoint['device_id'],
                            "notes" => $checkpoint['notes'],


                        ];
                        $routeId = RouteLocations::create($data);
                    }
                }
            }

            return $this->response("Successfully Updated", null, true);
        } else { //Create
            $isExist = Routes::where("name", $request->route_name)->count();
            if ($isExist) {

                return $this->response("Route Name is already exist", null, false);
            } else {
                DB::beginTransaction();
                try {

                    $data = [
                        "company_id" => $request->company_id,
                        "name" => $request->route_name,
                        "start_time" => $request->route_start_hour . ':' . $request->route_start_minute . ":00",
                        "notes" => $request->route_notes,
                        "repeat_minutes" => $request->repeat_minutes,
                        "sun" => $request->sun_checkbox,
                        "mon" => $request->mon_checkbox,
                        "tue" => $request->tue_checkbox,
                        "wed" => $request->wed_checkbox,
                        "thu" => $request->thu_checkbox,
                        "fri" => $request->fri_checkbox,
                        "sat" => $request->sat_checkbox,



                    ];
                    $routeId = Routes::create($data);
                    if ($routeId) {

                        foreach ($request->route_checkpoints as  $checkpoint) {
                            if ($checkpoint['device_id'] != '') {

                                $data = [
                                    "company_id" => $request->company_id,
                                    "route_id" => $routeId->id,
                                    // "schedule_time" => $checkpoint['start_hour'] . ':' . $checkpoint['start_minute'] . ":00",
                                    "device_id" => $checkpoint['device_id'],
                                    "notes" => $checkpoint['notes'],


                                ];
                                $routeId = RouteLocations::create($data);
                            }
                        }
                    }

                    DB::commit();

                    return $this->response("Successfully Created", null, true);
                } catch (\Throwable $th) {
                    DB::rollback();

                    return $this->response("Exception Occured", null, false);
                    //throw $th;
                }
            }
        }
    }
}
