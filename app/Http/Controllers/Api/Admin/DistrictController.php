<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\District;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        try {

            $perPage = $request->get('per_page', 10);   // default 10
            $query   = District::orderBy('dis_code', 'ASC');

            // 🔍 Filters
            if ($request->has('pro_code')) {
                $query->where('pro_code', $request->pro_code);
            }

            if ($request->has('dis_code')) {
                $query->where('dis_code', $request->dis_code);
            }

            if ($request->has('name_en')) {
                $query->where('name_en', 'LIKE', '%' . $request->name_en . '%');
            }

            if ($request->has('name_kh')) {
                $query->where('name_kh', 'LIKE', '%' . $request->name_kh . '%');
            }

            if ($request->has('active')) {
                $query->where('active', $request->active);
            }

            $data = $query->paginate($perPage);
            
            return response()->json([
                'message' => 'Data retrieved successfully',
                'data'    => $data->items(),   // actual records
                'meta'    => [
                    'current_page' => $data->currentPage(),
                    'last_page'    => $data->lastPage(),
                    'per_page'     => $data->perPage(),
                    'total'        => $data->total(),
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'មានបញ្ហាក្នុងការទាញយកទិន្នន័យ',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'dis_code'   => 'required|string|unique:sys_location_districts,dis_code',
                'name_en'    => 'required|string',
                'name_kh'    => 'nullable|string',
                'Reference'  => 'nullable|string',
                'created_by' => 'nullable|integer',
            ]);

            $data = District::create($request->all());

            return response()->json([
                'message' => 'Data created successfully',
                'data' =>  District::findOrFail($request->dis_code),
            ], 201);
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

    public function show($id)
    {
        try {
            $data = District::findOrFail($id);
            return response()->json([
                'message' => 'Data retrieved successfully',
                'data' => $data,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'មិនមានទិន្នន័យដែលអ្នកស្នើសុំពីនេះទេ',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = District::where('dis_code', $id)->firstOrFail();

            $request->validate([
                'pro_code'   => [
                    'required',
                    'string',
                    'max:2',
                    Rule::unique('sys_location_districts', 'pro_code')->ignore($data->pro_code, 'pro_code'),
                ],
                'dis_code'   => [
                    'required',
                    'string',
                    'max:4',
                    Rule::unique('sys_location_districts', 'dis_code')->ignore($data->dis_code, 'dis_code'),
                ],
                'name_en'    => 'required|string',
                'name_kh'    => 'required|string',
                'updated_by' => 'required|integer',
            ], [
                'pro_code.required' => 'លេខកូដទីតាំងត្រូវបានទាមទារ',
                'pro_code.unique' => 'លេខកូដទីតាំងនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
                'dis_code.required' => 'លេខកូដទីតាំងត្រូវបានទាមទារ',
                'dis_code.unique' => 'លេខកូដទីតាំងនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
                'name_en.required' => 'សូមបញ្ចូលឈ្មោះ',
                'name_kh.required' => 'សូមបញ្ចូលឈ្មោះ',
            ]);

            $data->update($request->all());
            return response()->json([
                'message' => 'Data updated successfully',
                'data' => $data,
            ], 200);
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

    public function destroy($id)
    {
        try {
            $data = District::findOrFail($id);
            if ($data->delete()) {
                return response()->json([
                    'message' => 'លុបបានជោគជ័យ',
                ], 200);
            } else {
                return response()->json([
                    'message' => 'លុបមិនបាន',
                ], 400);
            }
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

}
