<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use DB;


/**
 * Class AuthorizationController
 * @package App\Http\Controllers
 */
class APIGoogleMapController extends Controller
{
    /**
     * Constant to convert mile to kilometers
     */
    const MILE_TO_KILOMETERS = 6371;

    /**
     * @var array
     */
    protected $moscowKremlinCoordinates = [
        'latitude'  => '55.752023',
        'longitude' => '37.617499'
    ];

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function map()
    {
        return view('pages.google-map.map', ['center' => (object) $this->moscowKremlinCoordinates]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function markers()
    {
        $selectRaw = "coordinates.*, person.*, (
          ".static::MILE_TO_KILOMETERS." * ACOS(
            COS(radians({$this->moscowKremlinCoordinates['latitude']})) 
            * COS(RADIANS(`latitude`)) 
            * COS(RADIANS(`longitude`) - RADIANS({$this->moscowKremlinCoordinates['longitude']})) 
            + SIN(RADIANS({$this->moscowKremlinCoordinates['latitude']})) 
            * SIN(RADIANS(`latitude`)))
        ) AS distance";

        $markers = DB::table('map.coordinates')
            ->join('map.person', 'coordinates.person_id', '=', 'person.id')
            ->select(DB::raw($selectRaw))
            ->get();

        return response()->json($markers);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function people()
    {
        $selectRaw = "SUBSTRING_INDEX(SUBSTRING_INDEX(`fio`, ' ', -2), ' ', 1) AS `name`, 
                      COUNT(*) AS `repetitions`, 
                      GROUP_CONCAT(`id`) AS `ids`";

        $people = DB::table('map.person')
            ->select(DB::raw($selectRaw))
            ->groupBy('name')
            ->orderBy('name', 'ASC')
            ->get();

        return response()->json($people);
    }

}
