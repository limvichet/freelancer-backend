<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Position;
use App\Models\Professional;
use App\Models\ProfessionalType;
use App\Models\Qualification;
use App\Models\StaffStatus;
use App\Models\Subject;

class GetStaffController extends Controller
{
    public function getPositions(Request $request)
    {
        $data = Position::active()->select('position_id as id', 'position_kh as value')->orderBy('position_hierarchy', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getQualifications(Request $request)
    {
        $data = Qualification::active()->select('qualification_id as id', 'qualification_kh as value')->orderBy('qualification_hierarchy', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getSubjects(Request $request)
    {
        $data = Subject::active()->select('subject_id as id', 'subject_kh as value')->orderBy('subject_hierarchy', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getInstitutions(Request $request)
    {
        $data = Institution::active()->select('institution_id as id', 'institution_kh as value')->orderBy('institution_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getProfessionals(Request $request)
    {
        $data = Professional::active()->select('professional_id as id', 'professional_kh as value')->orderBy('professional_hierarchy', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getProfessionalTypes(Request $request)
    {
        $data = ProfessionalType::active()->select('professional_type_id as id', 'professional_type_kh as value')->orderBy('professional_type_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

    public function getStatus(Request $request)
    {
        $data = StaffStatus::active()->select('status_id as id', 'status_kh as value')->orderBy('status_id', 'asc')->get();
        $response = [
            'data' => $data,
            'code'  => config('constants.codes.success'),
            'message' => config('constants.messages_en.request_success')
        ];
        return response($response, 200);
    }

}
