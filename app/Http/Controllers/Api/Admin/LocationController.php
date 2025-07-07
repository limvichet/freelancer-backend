<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Commune;
use App\Models\Village;
use App\Models\District;
use App\Models\Location;
use App\Models\Province;
use App\Models\Religion;
use App\Models\LocationType;
use Illuminate\Http\Request;
use App\Models\LocationLevel;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function getProvinces(Request $request)
    {
        $data = Province::active()->select('pro_code as id', 'name_kh as value')->orderBy('pro_code', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getDistricts(Request $request)
    {
        $pro_code = $request->pro_code;
        $data = District::
            when($pro_code <> '', function($query) use ($pro_code) {
                $query->where('pro_code', $pro_code);
            })
            ->where('active', 1)->select('dis_code as id', 'name_kh as value')->orderBy('dis_code', 'asc')
            ->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

    public function getCommunes(Request $request)
    {
        $dis_code = $request->dis_code;
        $data = Commune::
            when($dis_code <> '', function($query) use ($dis_code) {
                $query->where('dis_code', $dis_code);
            })->where('active', 1)->select('com_code as id', 'name_kh as value')->orderBy('com_code', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

    public function getVillages(Request $request)
    {
        $com_code = $request->com_code;
        $data = Village::
            when($com_code <> '', function($query) use ($com_code) {
                $query->where('com_code', $com_code);
            })->where('active', 1)->select('vil_code as id', 'name_kh as value')->orderBy('vil_code', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

    public function getLocations(Request $request)
    {
        $pro_code = $request->pro_code;
        $data = Location::
            when($pro_code <> '', function($query) use ($pro_code) {
                $query->where('pro_code', $pro_code);
            })
            ->where('active', 1)->select('location_code as id', 'location_kh as value')->orderBy('location_code', 'asc')
            ->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

    public function getLocationTypes(Request $request)
    {
        $data = LocationType::select('location_type_id as id', 'location_type_kh as value')->orderBy('location_type_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

    public function getLocationRegions(Request $request)
    {
        $data = Religion::select('region_id as id', 'region_kh as value')->orderBy('region_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

    public function getLocationLevels(Request $request)
    {
        $data = LocationLevel::select('edu_level_id as id', 'edu_level_kh as value')->orderBy('edu_level_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages.request_success')
        ];
        return response($response, 200);
    }

}
