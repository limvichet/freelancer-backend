<?php

namespace App\Http\Controllers\Api\Admin\Timetables;

use App\Models\Grade;
use App\Models\Staff;
use App\Models\Academic;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Timetables\TimetableManageGrade;

class TimetableManageGradeController extends Controller
{
    //
    public function __construct(){}

    // Index
    public function index(Request $request)
    {

        $data = TimetableManageGrade::where('academic_id', $request->academic_id)
            ->where('location_code', $request->location_code)
            ->when($request->grade_id, function ($query) use ($request) {
                $query->where('grade_id', $request->grade_id);
            })
            ->orderBy('grade_id')
            ->orderBy('grade_name')
            ->paginate(20);

        return response()->json([
            'timetable_grades'   => $data,
        ]);

    }


    // Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_id' => 'required',
            'grade_id'    => 'required',
            'grade_name'  => 'required|array',
        ]);

        $locationCode = $request->location_code;
        $mainGradeName = $request->grade_name[0] ?? null;

        $created = [];
        $skipped = [];

        foreach ($request->grade_name as $gradeName) {

            if (empty($gradeName)) {
                continue;
            }

            $convertedGradeName = preg_match("/^[\w\d\s.,-]*$/", $gradeName)
                ? strtoupper($gradeName)
                : $gradeName;

            $existingGrade = TimetableManageGrade::where([
                'academic_id'   => $request->academic_id,
                'location_code' => $locationCode,
                'grade_id'      => $request->grade_id,
                'grade_name'    => $convertedGradeName,
            ])->first();

            if ($existingGrade) {
                $skipped[] = $convertedGradeName;
                continue;
            }

            TimetableManageGrade::create([
                'location_code' => $locationCode,
                'academic_id'   => $request->academic_id,
                'grade_id'      => $request->grade_id,
                'grade_name'    => $convertedGradeName,
                'created_by'    => $request->created_by,
                'updated_by'    => $request->updated_by,
                'created_at'    => $request->created_at,
                'updated_at'    => $request->updated_at,
            ]);

            $created[] = $convertedGradeName;
        }

        return response()->json([
            'status'  => true,
            'message' => 'Grades processed successfully',
            'data'    => [
                'created' => [
                    'location_code' => $locationCode,
                    'academic_id' => $request->academic_id,
                    'grade_id'    => $request->grade_id,
                    'grade_name'  => $request->grade_name,
                    'created_by'    => $request->created_by,
                    'updated_by'    => $request->updated_by,
                    'created_at'    => $request->created_at,
                    'updated_at'    => $request->updated_at,
                ],
            ]
        ], 201);
    }

    // Edit
    public function show($id){
        try {
            $data = TimetableManageGrade::findOrFail($id);
            return response()->json([
                'timetable_grades'   => $data,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'មិនមានទិន្នន័យដែលអ្នកស្នើសុំពីនេះទេ',
            ], 404);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'tgrade_id'  => 'required',
            ]);

            $data = TimetableManageGrade::where('tgrade_id', $id)->firstOrFail();

            // Convert grade name (same logic as store)
            $convertedGradeName = preg_match("/^[\w\d\s.,-]*$/", $request->grade_name)
                ? strtoupper($request->grade_name)
                : $request->grade_name;

            // Check if grade_name already exists (exclude current record)
            $exists = TimetableManageGrade::where([
                'academic_id'   => $request->academic_id,
                'location_code' => $request->location_code,
                'grade_id'      => $request->grade_id,
                'grade_name'    => $convertedGradeName,
            ])
                ->where('tgrade_id', '!=', $id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Grade name already exists',
                ], 409);
            }

            // Update allowed fields only
            $data->update([
                'grade_name' => $convertedGradeName,
                'grade_id' => $request->grade_id ?? $data->grade_id,
                'updated_by' => $request->updated_by ?? $data->updated_by,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Grade updated successfully',
                'data' => $data
            ]);
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


    // Destroy
    public function destroy($id)
    {
        $data = TimetableManageGrade::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'លុបទិន្នន័យបានជោគជ័យ'
        ], 200); // or 204 with null
    }

}
