<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::
                        when(request()->pro_code, function($q) {
                            $q->where('pro_code',request()->pro_code)
                            ->orderBy('location_kh', 'asc');;
                        })
                        ->when(request()->dis_code, function($q) {
                            $q->where('dis_code',request()->dis_code)
                            ->orderBy('location_kh', 'asc');;
                        })
                        ->when(request()->com_code, function($q) {
                            $q->where('com_code',request()->com_code)
                            ->orderBy('location_kh', 'asc');
                        })
                        ->when(request()->vil_code, function($q) {
                            $q->where('vil_code',request()->vil_code)
                            ->orderBy('location_kh', 'asc');
                        })
                        ->when(request()->location_type_id, function($q) {
                            $q->where('location_type_id', request()->location_type_id)
                            ->orderBy('location_kh', 'asc');;
                        })
                        ->when(request()->name, function($q) {
                            $q->where(function($q) {
                                $q->Where('location_kh', 'LIKE', '%'.request()->name.'%')
                                ->orWhere('location_code', 'LIKE', '%'.request()->name.'%')
                                ->orderBy('location_kh', 'asc');
                            });
                        })
                        ->orderBy('location_kh', 'asc')
                        ->paginate(20);
        return LocationResource::collection($locations);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'location_code' => 'required|string|max:11|unique:sys_locations,location_code',
                // Add other fields and rules if needed
            ], [
                'location_code.required' => 'លេខកូដទីតាំងត្រូវបានទាមទារ',
                'location_code.unique' => 'លេខកូដទីតាំងនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
            ]);

            $location = Location::create($request->all());
            return new LocationResource($location);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'បញ្ហាក្នុងការផ្ទៀងផ្ទាត់ទិន្នន័យ',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'មានបញ្ហាផ្ទៃក្នុងម៉ាស៊ីនមេ',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function show($location_code)
    {
        try {
            $location = Location::findOrFail($location_code);
            return new LocationResource($location);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'មិនមានទិន្នន័យដែលអ្នកស្នើសុំពីនេះទេ',
            ], 404);
        }
    }

    public function update(Request $request, $location_code)
    {
        try {
            $location = Location::where('location_code', $location_code)->firstOrFail();

            $request->validate([
                'location_code' => [
                    'required',
                    'string',
                    'max:11',
                    Rule::unique('sys_locations', 'location_code')->ignore($location->location_code, 'location_code'),

                ],
                // Add other validation rules if needed
            ], [
                'location_code.required' => 'លេខកូដទីតាំងត្រូវបានទាមទារ',
                'location_code.unique' => 'លេខកូដទីតាំងនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
            ]);

            $location->update($request->all());

            return new LocationResource($location);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'មិនមានទិន្នន័យដែលអ្នកចង់កែប្រែទេ'
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'បញ្ហាក្នុងការផ្ទៀងផ្ទាត់ទិន្នន័យ',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'មានបញ្ហាផ្ទៃក្នុងម៉ាស៊ីនមេ',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function destroy($location_code)
    {
        $location = Location::findOrFail($location_code);
        $location->delete();

        return response()->json([
            'message' => 'លុបទិន្នន័យបានជោគជ័យ'
        ], 200); // or 204 with null
    }

}
