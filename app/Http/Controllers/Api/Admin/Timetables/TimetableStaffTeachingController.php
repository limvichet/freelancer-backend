<?php

namespace App\Http\Controllers\Api\Admin\Timetables;

use App\Models\Grade;
use App\Models\Staff;
use App\Models\Academic;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Models\ClassGradeType;
use App\Http\Controllers\Controller;
use App\Models\Timetables\TimetableGrade;
use App\Models\Timetables\TimetableStaffTeaching;
use App\Models\Timetables\TimetableGeneratedPrimary;

class TimetableStaffTeachingController extends Controller
{
    //
    public function __construct(){}

    // Index
    public function index(Request $request)
    {

        $data = TimetableStaffTeaching::where('academic_id', $request->academic_id)
            ->where('location_code', $request->location_code)
            ->when($request->staff_id, function ($query) use ($request) {
                $query->where('staff_id', $request->staff_id);
            })
            ->orderBy('staff_id')
            ->paginate(20);

        return response()->json([
            'data'   => $data,
        ]);

    }


    // Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id'    => 'required',
            'location_code' => 'required',
            'academic_id'   => 'required',
        ]);

        $data = [
            'staff_id'        => $request->staff_id,
            'location_code'   => $request->location_code,
            'academic_id'     => $request->academic_id,
            'add_teaching'    => $request->add_teaching,
            'class_incharge'  => $request->class_incharge,
            'chief_technical' => $request->chief_technical,
            'bi_language'     => $request->bi_language,
            'teach_english'   => $request->teach_english,
            'cgt_id'          => $request->cgt_id,
            'created_by'      => $request->created_by,
            'updated_by'      => $request->updated_by,
            'created_at'      => $request->created_at,
            'updated_at'      => $request->updated_at,
        ];

        $record = TimetableStaffTeaching::create($data);

        // timetable generated primary
        $eduLevelId =  (int) Location::where('location_code', $data['location_code'])->value('edu_level_id');
        if(in_array($eduLevelId, [1, 2])){
            $cgtCode = (int) ClassGradeType::where('cgt_id', $data['cgt_id'])->value('cgt_code');

            if ($cgtCode <= 0) return response()->json(['message' => 'Invalid cgt_code'], 400);

            $dataGenerated = [
                'location_code' => $data['location_code'],
                'academic_id'   => $data['academic_id'],
                'staff_id'      => $data['staff_id'],
                'created_by'    => $request->created_by,
                'updated_by'    => $request->updated_by,
                'created_at'    => $request->created_at,
                'updated_at'    => $request->updated_at,
            ];

            $generatedRecords = [];
            for ($i = 0; $i < $cgtCode; $i++) {
                $generatedRecords[] = $dataGenerated;
            }

            TimetableGeneratedPrimary::where('location_code', $data['location_code'])
                ->where('academic_id', $data['academic_id'])
                ->where('staff_id', $data['staff_id'])
                ->delete();

            TimetableGeneratedPrimary::insert($generatedRecords);
            // return response()->json(['message' => "$cgtCode records created successfully"]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Timetable staff teaching created successfully',
            'data'    => $record,
        ], 201);
    }


    // Edit
    public function show($id){
        try {
            $data = TimetableStaffTeaching::findOrFail($id);
            return response()->json([
                'data'   => $data,
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

            $data = TimetableStaffTeaching::where('tteaching_id', $id)->firstOrFail();

            // Update allowed fields only
            $data->update([
                'staff_id'      => $request->staff_id,
                'location_code'   => $request->location_code,
                'academic_id'     => $request->academic_id,
                'add_teaching'    => $request->add_teaching,
                'class_incharge'  => $request->class_incharge,
                'chief_technical' => $request->chief_technical,
                'bi_language'     => $request->bi_language,
                'teach_english'   => $request->teach_english,
                'cgt_id'          => $request->cgt_id,
                'created_by'      => $request->created_by,
                'updated_by'      => $request->updated_by,
                'created_at'      => $request->created_at,
                'updated_at'      => $request->updated_at,
            ]);

            // timetable generated primary
            $eduLevelId =  (int) Location::where('location_code', $data['location_code'])->value('edu_level_id');
            if (in_array($eduLevelId, [1, 2])) {
                $cgtCode = (int) ClassGradeType::where('cgt_id', $data['cgt_id'])->value('cgt_code');

                if ($cgtCode <= 0) return response()->json(['message' => 'Invalid cgt_code'], 400);

                $dataGenerated = [
                    'location_code' => $data['location_code'],
                    'academic_id'   => $data['academic_id'],
                    'staff_id'      => $data['staff_id'],
                    'created_by'    => $request->created_by,
                    'updated_by'    => $request->updated_by,
                    'created_at'    => $request->created_at,
                    'updated_at'    => $request->updated_at,
                ];

                $generatedRecords = [];
                for ($i = 0; $i < $cgtCode; $i++) {
                    $generatedRecords[] = $dataGenerated;
                }

                TimetableGeneratedPrimary::where('location_code', $data['location_code'])
                    ->where('academic_id', $data['academic_id'])
                    ->where('staff_id', $data['staff_id'])
                    ->delete();

                TimetableGeneratedPrimary::insert($generatedRecords);
                // return response()->json(['message' => "$cgtCode records created successfully"]);
            }

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
        $data = TimetableStaffTeaching::findOrFail($id);
        $data->delete();

        return response()->json([
            'message' => 'លុបទិន្នន័យបានជោគជ័យ'
        ], 200); // or 204 with null
    }


    // Bulk Update Generated Primary Timetables
    public function generatePrimaryTimetable(Request $request)
    {
        $dataList = $request->input('dts', []);

        foreach ($dataList as $data) {
            // Make sure tprimary_id exists
            if (!isset($data['tprimary_id'])) continue;

            $updateData = [
                'location_code' => $data['location_code'] ?? null,
                'academic_id'   => $data['academic_id'] ?? null,
                'staff_id'      => $data['staff_id'] ?? null,
                'tgrade_id'     => $data['tgrade_id'] ?? null,
                'is_organized'  => $data['is_organized'] ?? 0,
                'created_by'    => $data['created_by'] ?? null,
                'updated_by'    => $data['updated_by'] ?? null,
                'created_at'    => $data['created_at'] ?? null,
                'updated_at'    => $data['updated_at'] ?? null,
            ];

            // Update each record
            TimetableGeneratedPrimary::where('tprimary_id', $data['tprimary_id'])->update($updateData);
        }

        return response()->json(['message' => 'Bulk update completed successfully']);
    }

}
