<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Village;

class VillageController extends Controller
{
    public function index(Request $request)
    {
        try {

            $perPage = $request->get('per_page', 10);   // default 10
            $query   = Village::orderBy('vil_code', 'ASC');

            // 🔍 Filters

            if ($request->has('com_code')) {
                $query->where('com_code', $request->com_code);
            }

            if ($request->has('vil_code')) {
                $query->where('vil_code', $request->vil_code);
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
                'vil_code'   => 'required|string|unique:sys_location_villages,vil_code',
                'name_en'    => 'required|string',
                'name_kh'    => 'nullable|string',
                'Reference'  => 'nullable|string',
                'created_by' => 'nullable|integer',
            ]);

            $data = Village::create($request->all());

            return response()->json([
                'message' => 'Data created successfully',
                'data' =>  Village::findOrFail($request->vil_code),
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
            $data = Village::findOrFail($id);
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
            $data = Village::where('vil_code', $id)->firstOrFail();

            $request->validate([
                'com_code'   => [
                    'required',
                    'string',
                    'max:6',
                    Rule::unique('sys_location_villages', 'com_code')->ignore($data->com_code, 'com_code'),
                ],
                'vil_code'   => [
                    'required',
                    'string',
                    'max:8',
                    Rule::unique('sys_location_villages', 'vil_code')->ignore($data->vil_code, 'vil_code'),
                ],
                'name_en'    => 'required|string',
                'name_kh'    => 'required|string',
                'updated_by' => 'required|integer',
            ], [
                'com_code.required' => 'លេខកូដទីតាំងត្រូវបានទាមទារ',
                'com_code.unique' => 'លេខកូដទីតាំងនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
                'vil_code.required' => 'លេខកូដទីតាំងត្រូវបានទាមទារ',
                'vil_code.unique' => 'លេខកូដទីតាំងនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
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
            $data = Village::findOrFail($id);
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
