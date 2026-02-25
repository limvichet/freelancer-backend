<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Grade;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\Academic;
use App\Models\Position;
use App\Models\Institution;
use App\Models\SalaryLevel;
use App\Models\StaffStatus;
use App\Models\Professional;
use Illuminate\Http\Request;
use App\Models\Qualification;
use App\Models\ProfessionalType;
use App\Http\Controllers\Controller;
use App\Models\ClassGradeType;

class GetClassController extends Controller
{
    public function getAcademics(Request $request)
    {
        $data = Academic::select('year_id as id', 'year_kh as value')->orderBy('year_id', 'desc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getGrades(Request $request)
    {
        $data = Grade::join('sys_locations as location','location.edu_level_id','sys_location_grades.edu_level_id')
            ->select('grade_id as id', 'grade_kh as value')
            ->where('location.location_code', $request->location_code)
            ->orderBy('grade_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getStaffs(Request $request)
    {
        $data = Staff::select('staff_id as id', 'staff_name as value')
            ->where('location_code', $request->location_code)
            ->orderBy('staff_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getClassGradeType(Request $request)
    {
        $data = ClassGradeType::select('cgt_id as id', 'cgt_name as value')
            ->orderBy('cgt_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

}
