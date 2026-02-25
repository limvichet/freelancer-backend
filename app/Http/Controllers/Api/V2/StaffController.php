<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function staffProfile(Request $request)
    {
        $lang = $request->lang;
        $query =  DB::table('sys_staffs')->where('sys_staffs.staff_id', $request->staff_id);
        if ($query) {

            $data = (clone $query)
                ->leftJoin('sys_staff_positions', 'sys_staff_positions.position_id', '=', 'sys_staffs.position_id')
                ->leftJoin('sys_staff_status', 'sys_staff_status.status_id', '=', 'sys_staffs.status_id')
                ->leftJoin('sys_locations', 'sys_locations.location_code', '=', 'sys_staffs.location_code')
                ->select(
                    'sys_staffs.staff_id',
                    'sys_staffs.staff_name',
                    DB::raw("IF(sys_staffs.staff_gender=1, 'ប្រុស', 'ស្រី') as staff_gender"),
                    DB::raw("DATE_FORMAT(sys_staffs.staff_dob, '%d-%m-%Y') as staff_dob"),
                    'sys_staff_positions.position_kh as staff_position',
                    "payroll_id as staff_payroll",
                    "staff_email",
                    "staff_phone",
                    "sys_staff_status.status_kh as staff_status",
                    "sys_locations.location_kh as staff_location",
                )->first();

            $qualData =  (clone $query)
                ->leftJoin('sys_staff_qualifications as start_qual', 'start_qual.qualification_id', '=', 'sys_staffs.start_qualification_id')
                ->leftJoin('sys_staff_institutions as start_qual_inst', 'start_qual_inst.institution_id', '=', 'sys_staffs.start_qualification_institution_id')
                ->leftJoin('sys_staff_qualifications as current_qual', 'current_qual.qualification_id', '=', 'sys_staffs.current_qualification_id')
                ->leftJoin('sys_staff_subjects as current_qual_sub', 'current_qual_sub.subject_id', '=', 'sys_staffs.current_qualification_subject_id')
                ->leftJoin('sys_staff_institutions as current_qual_inst', 'current_qual_inst.institution_id', '=', 'sys_staffs.current_qualification_institution_id')
                ->select(
                    'start_qual.qualification_kh as start_qualification',
                            DB::raw("DATE_FORMAT(sys_staffs.start_qualification_date, '%d-%m-%Y') as start_qualification_date"),
                            "start_qual_inst.institution_kh as start_qualification_institution",
                            'current_qual.qualification_kh as current_qualification',
                            DB::raw("DATE_FORMAT(sys_staffs.current_qualification_date, '%d-%m-%Y') as current_qualification_date"),
                            "current_qual_sub.subject_kh as current_qualification_subject",
                            "current_qual_inst.institution_kh as current_qualification_institution",
                )->first();

            $profData = (clone $query)
                ->leftJoin('sys_staff_professionals as start_prof', 'start_prof.professional_id', '=', 'sys_staffs.start_professional_id')
                ->leftJoin('sys_staff_subjects as start_prof_sub', 'start_prof_sub.subject_id', '=', 'sys_staffs.start_professional_subject_id_1')
                ->leftJoin('sys_staff_institutions as start_prof_inst', 'start_prof_inst.institution_id', '=', 'sys_staffs.start_professional_institution_id')
                ->leftJoin('sys_staff_professionals as current_prof', 'current_prof.professional_id', '=', 'sys_staffs.current_professional_id')
                ->leftJoin('sys_staff_subjects as current_prof_sub', 'current_prof_sub.subject_id', '=', 'sys_staffs.current_professional_subject_id_1')
                ->leftJoin('sys_staff_institutions as current_prof_inst', 'current_prof_inst.institution_id', '=', 'sys_staffs.current_professional_institution_id')
                ->select(
                    "start_prof.professional_kh as start_professional",
                    DB::raw("DATE_FORMAT(sys_staffs.start_professional_date, '%d-%m-%Y') as start_professional_date"),
                    "start_prof_sub.subject_kh as start_professional_subject",
                    "start_prof_inst.institution_kh as start_professional_institution",
                    "current_prof.professional_kh as current_professional",
                    DB::raw("DATE_FORMAT(sys_staffs.current_professional_date, '%d-%m-%Y') as current_professional_date"),
                    "current_prof_sub.subject_kh as current_professional_subject",
                    "current_prof_inst.institution_kh as current_professional_institution",
                )->first();

            $data->qualification = $qualData;
            $data->professional = $profData;

            $response = [
                'data'  => $data,
                'code'  => config('constants.codes.success'),
                'message' => $lang == 'en' ? config('constants.messages_en.request_success') : config('constants.messages.request_success')
            ];
            return response($response, 200);
        } else {
            return response([
                'code'  => config('constants.codes.fail_404'),
                'message' => $lang == 'en' ? config('constants.messages_en.request_fail') : config('constants.messages.request_fail')
            ], 404);
        }
    }

}
