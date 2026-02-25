<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;

class StaffController extends Controller
{
    public function index()
    {
        $data = Staff::when(request()->staff_id, function ($q) {
                $q->where('staff_id', request()->staff_id)
                    ->orderBy('staff_id', 'desc');
            })
            ->when(request()->staff_name, function ($q) {
                $q->where(function ($q) {
                    $q->Where('staff_name', 'LIKE', '%' . request()->staff_name . '%')
                        ->orderBy('staff_id', 'desc');
                });
            })
            ->where('location_code', request()->location_code)
            ->orderBy('staff_id', 'desc')
            ->paginate(20);
        return StaffResource::collection($data);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'staff_name' => 'required|string',
                'staff_gender' => 'required',
                'staff_dob' => 'nullable|date',
                'staff_phone' => 'nullable|string',
                'staff_email' => 'nullable|email',
            ]);

            $data = Staff::create($request->all());
            return new StaffResource($data);

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
            $data = Staff::findOrFail($id);
            return new StaffResource($data);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'មិនមានទិន្នន័យដែលអ្នកស្នើសុំពីនេះទេ',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = Staff::where('staff_id', $id)->firstOrFail();

            $request->validate([
                'staff_id' => [
                    'required',
                    Rule::unique('sys_staffs', 'staff_id')->ignore($data->staff_id, 'staff_id'),
                ],
                // Add other validation rules if needed
            ], [
                'staff_id.required' => 'លេខកូដត្រូវបានទាមទារ',
                'staff_id.unique' => 'លេខកូដនេះមានរួចហើយ សូមប្រើមួយផ្សេងទៀត',
            ]);

            $data->update($request->all());

            return new StaffResource($data);
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
        $data = Staff::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'លុបទិន្នន័យបានជោគជ័យ'
        ], 200); // or 204 with null
    }
}
