<?php

namespace App\Http\Controllers\Api\V2;

use App\Constants\CheckStatus;
use App\Constants\CpdStatus;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivitiesListRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Models\CPD\LearningOption;
use App\Models\CPD\SubjectOfStudy;
use App\Models\CPD\Course;
use App\Models\CPD\ScheduleCourse;
use App\Models\CPD\EnrollmentCourse;
use Carbon\Carbon;

class CpdController extends Controller
{
    private static $item_per_page = 20;

    public function __construct()
    {
        //$this->middleware('auth:sanctum');
    }

    //Public APIs
    public function getLearningModeOptions($lang)
    {
        $key_order = $lang == 'en' ? 'learning_option_en' : 'learning_option_kh';

        $options = LearningOption::select('learning_option_id as id', "{$key_order} as value")
            ->orderBy($key_order)
            ->get();
        return ApiResponse::success('', null, $options);
    }

    // List subjects with active courses
    public function subjectsList()
    {
        $subjects = DB::table('cpd_subjects')
        ->join('cpd_field_studies', 'cpd_subjects.cpd_field_id', '=', 'cpd_field_studies.cpd_field_id')
        ->leftJoin('cpd_course_relations', 'cpd_subjects.cpd_subject_id', '=', 'cpd_course_relations.cpd_subject_id')
        ->leftJoin('cpd_schedule_courses', function ($join) {
            $join->on('cpd_course_relations.cpd_course_id', '=', 'cpd_schedule_courses.cpd_course_id')
                 ->where('cpd_schedule_courses.reg_start_date', '<=', date("Y-m-d"))
                 ->where('cpd_schedule_courses.end_date', '>=', date("Y-m-d"));
        })
        ->select(
            'cpd_subjects.cpd_subject_id as id',
            'cpd_subjects.cpd_subject_code as code',
            'cpd_subjects.cpd_subject_kh as name',
            'cpd_subjects.cpd_subject_en as name_en',
            'cpd_field_studies.cpd_field_code as field_code',
            'cpd_field_studies.cpd_field_kh as field_kh',
            'cpd_field_studies.cpd_field_en as field_en'
        )
        ->selectRaw('COUNT(DISTINCT cpd_schedule_courses.schedule_course_id) as courseCount')
        ->groupBy('cpd_subjects.cpd_subject_id', 'cpd_subjects.cpd_subject_kh', 'cpd_subjects.cpd_subject_en')
        ->orderByDesc('courseCount')
        ->orderBy('cpd_subjects.cpd_subject_kh')
        ->get()
        ->map(function ($subject) {
            $subject->hasActive = $subject->courseCount > 0;
            return $subject;
        });

    return ApiResponse::success('', null, $subjects);

    }

    public static function searchActivities($lang, ActivitiesListRequest $request)
    {
        $limit = $request->item_per_page ? $request->item_per_page : self::$item_per_page;
        $skip = ($request->page - 1) * $limit;

        $data = ScheduleCourse::join('cpd_courses as t2', 'cpd_schedule_courses.cpd_course_id', '=', 't2.cpd_course_id')
            ->join('cpd_learning_options as t3', 'cpd_schedule_courses.learning_option_id', '=', 't3.learning_option_id')
            ->join('cpd_providers as t4', 'cpd_schedule_courses.provider_id', '=', 't4.provider_id')
            ->leftJoin('sys_qualification_codes as t5', 'cpd_schedule_courses.qualification_code', '=', 't5.qualification_code')
            ->leftJoin('sys_provinces as pro', 'cpd_schedule_courses.pro_code', '=', 'pro.pro_code')
            ->leftJoin('sys_districts as dis', 'cpd_schedule_courses.dis_code', '=', 'dis.dis_code');

        // Apply filters based on the validated request object
        if ($request->filled('provider_id')) {
            $data->where('cpd_schedule_courses.provider_id', $request->provider_id);
        }
        if ($request->filled('subject_id')) {
            $data->whereIn('cpd_schedule_courses.cpd_course_id', function ($query) use ($request) {
                $query->select('cpd_course_id')
                    ->from('cpd_course_relations')
                    ->where('cpd_subject_id', $request->subject_id);
            });
        }
        if ($request->filled('providers')) {
            $data->whereIn('cpd_schedule_courses.provider_id', $request->providers);
        }
        if ($request->filled('provinces')) {
            $data->whereIn('cpd_schedule_courses.pro_code', $request->provinces);
        }
        if ($request->filled('modes')) {
            $data->whereIn('cpd_schedule_courses.learning_option_id', $request->modes);
        }

        if ($request->filled('keyword')) {
            $data->where(function ($query) use ($request) {
                $query->where('cpd_course_kh', 'like', '%' . $request->keyword . '%')
                    ->orWhere('cpd_course_en', 'like', '%' . $request->keyword . '%')
                     ->orWhere('cpd_course_code', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->is_active === 'active') {
            $data->whereRaw("curdate() BETWEEN cpd_schedule_courses.reg_start_date AND cpd_schedule_courses.end_date");
        } else if ($request->is_active === 'inactive') {
            $data->whereRaw("curdate() NOT BETWEEN cpd_schedule_courses.reg_start_date AND cpd_schedule_courses.end_date");
        }

        // Final query execution and pagination
        $data = $data->select(
            'cpd_schedule_courses.schedule_course_id as id',
            't2.cpd_course_id',
            'cpd_course_code',
            'cpd_course_kh',
            'cpd_course_en',
            //'cpd_course_desc_kh',
            't4.provider_id',
            't4.provider_kh',
            'credits',
            'duration_hour',
            't3.learning_option_id',
            'learning_option_kh',
            'learning_option_en',
            'pro.name_kh as province',
            'dis.name_kh as district',
            'address',
            't5.qualification_kh',
            DB::raw("DATE_FORMAT(cpd_schedule_courses.reg_start_date, '%d/%m/%Y') As reg_start_date"),
            DB::raw("DATE_FORMAT(cpd_schedule_courses.reg_end_date, '%d/%m/%Y') As reg_end_date"),
            DB::raw("DATE_FORMAT(cpd_schedule_courses.start_date, '%d/%m/%Y') As start_date"),
            DB::raw("DATE_FORMAT(cpd_schedule_courses.end_date, '%d/%m/%Y') As end_date"),
            DB::raw("(CASE WHEN curdate() BETWEEN cpd_schedule_courses.reg_start_date AND cpd_schedule_courses.end_date THEN 1 ELSE 0 END) As is_active"),
            DB::raw("(CASE WHEN curdate() BETWEEN cpd_schedule_courses.reg_start_date AND cpd_schedule_courses.reg_end_date THEN 1 ELSE 0 END) As is_registration_active"),
        )
            ->orderBy('is_active', 'DESC')
            ->orderBy('is_registration_active', 'DESC')
            ->orderBy('cpd_schedule_courses.reg_start_date', 'ASC')
            ->orderBy('cpd_course_kh', 'ASC')
            ->get()->slice($skip, $limit)->values()->all();

        return ApiResponse::success('', null, $data);
    }


    public function activityDetails($lang, $id, Request $request)
    {
        $scheduledCourse = ScheduleCourse::join('cpd_courses as t2', 'cpd_schedule_courses.cpd_course_id', '=', 't2.cpd_course_id')
            ->where('schedule_course_id', $id)
            ->select(
                'schedule_course_id',
                't2.cpd_course_id',
                'participant_num',
                'cpd_schedule_courses.learning_option_id',
                'teacher_educator',
                'partner_type_id',
                'pro_code',
                'dis_code',
                'address',
                'cpd_schedule_courses.provider_id',
                'cpd_schedule_courses.qualification_code',
                DB::raw("DATE_FORMAT(cpd_schedule_courses.reg_start_date, '%d/%m/%Y') As reg_start_date"),
                DB::raw("DATE_FORMAT(cpd_schedule_courses.reg_end_date, '%d/%m/%Y') As reg_end_date"),
                DB::raw("DATE_FORMAT(cpd_schedule_courses.start_date, '%d/%m/%Y') As start_date"),
                DB::raw("DATE_FORMAT(cpd_schedule_courses.end_date, '%d/%m/%Y') As end_date"),
                DB::raw("(CASE WHEN curdate() BETWEEN cpd_schedule_courses.start_date AND cpd_schedule_courses.end_date THEN 1 ELSE 0 END) As is_active"),
                DB::raw("(CASE WHEN curdate() BETWEEN cpd_schedule_courses.reg_start_date AND cpd_schedule_courses.reg_end_date THEN 1 ELSE 0 END) As is_registration_active"),
            )
            ->first();

        $course = $scheduledCourse->CPDCourse;
        $qualification = $scheduledCourse->qualification;
        $province = $scheduledCourse->province;
        $district = $scheduledCourse->district;
        $learningOption = $scheduledCourse->learningOption;
        $provider = $scheduledCourse->provider;
        $targetAudiencesPosition = $scheduledCourse->targetAudiencesPosition();

        $enroll = null;
        if ($request->payroll_id) {
            $enroll = EnrollmentCourse::join('cpd_schedule_courses', 'cpd_schedule_courses.schedule_course_id', '=', 'cpd_enrollment_courses.schedule_course_id')
                ->leftJoin('cpd_reject_reasons as t2', 'cpd_enrollment_courses.reason_id', '=', 't2.reason_id')
                ->where('cpd_enrollment_courses.schedule_course_id', $request->id)
                ->where('payroll_id', $request->payroll_id)
                ->select(
                    'id',
                    'is_verified',
                    'verified_date',
                    'confirm_completed',
                    'completed_date',
                    'provider_status',
                    'enroll_status_id',
                    't2.reason_id',
                    'reason_kh',
                    'reason_en',
                    'other_reason'
                )
                ->first();
        }

        $address = $province->name_kh;
        if(!empty($scheduledCourse->address))
            $address .= ' ('.$scheduledCourse->address.')';

        $data = [
            'id'    => $scheduledCourse->schedule_course_id,
            'code'  => $course->cpd_course_code,
            'name'  => $course->cpd_course_kh,
            'cdp_code' => null, //'170',
            'cdp_link' => null, //'https://cdp.moeys.gov.kh/enrol/cpdlist/view.php?id=170',
            'description'  => $course->cpd_course_desc_kh,
            'cpd_type'  => $course->courseType->cpd_course_type_kh,
            'learning_mode'  => $learningOption->learning_option_kh,
            'num_hours' => $course->duration_hour,
            'num_credits'   => $course->credits,
            'num_participants' => $scheduledCourse->participant_num,
            'teacher_educator' => $scheduledCourse->teacher_educator,
            'reg_start_date' => $scheduledCourse->reg_start_date,
            'reg_end_date'   => $scheduledCourse->reg_end_date,
            'start_date'   => $scheduledCourse->start_date,
            'end_date'     => $scheduledCourse->end_date,
            'is_active'      => $scheduledCourse->is_active,
            'is_registration_active' => $scheduledCourse->is_registration_active,
            'qualification' => $qualification->qualification_kh,
            'address'   => $address,
            'enroll'   => $enroll,
            'provider'  => $provider,

            'target_audiences' => $targetAudiencesPosition,
        ];

        return ApiResponse::success('', null, $data);
    }

    //End Public APIs

    //Secure APIs (required token)
    // Get only in-progress and credits not awarded
    public function viewOwnCPDList($lang, $payroll_id)
    {
        $data = EnrollmentCourse::join('cpd_schedule_courses', 'cpd_schedule_courses.schedule_course_id', '=', 'cpd_enrollment_courses.schedule_course_id')
            ->join('cpd_courses as t2', 'cpd_schedule_courses.cpd_course_id', '=', 't2.cpd_course_id')
            ->where('payroll_id', $payroll_id)
            ->where('provider_status', CheckStatus::APPROVED)
            ->where('is_verified', 0)
            ->whereIn('enroll_status_id', [CpdStatus::IN_PROGRESS, CpdStatus::COMPLETED])
            ->select(
                'cpd_schedule_courses.schedule_course_id',
                'cpd_course_kh as activity_name',
                'cpd_course_code',
                'credits',
                'enroll_status_id'
            )
            ->orderBy('cpd_schedule_courses.start_date', 'ASC')
            ->get();

        $message = $lang == 'en' ? config('constants.messages_en.request_success') : config('constants.messages.request_success');
        return ApiResponse::success($message, config('constants.codes.success'), $data);
    }

    public function viewCPDCredits($lang, $payroll_id)
    {
        $data = EnrollmentCourse::join('cpd_schedule_courses', 'cpd_schedule_courses.schedule_course_id', '=', 'cpd_enrollment_courses.schedule_course_id')
            ->join('cpd_courses as t2', 'cpd_schedule_courses.cpd_course_id', '=', 't2.cpd_course_id')
            ->where('payroll_id', $payroll_id)
            ->where('confirm_completed', 1)
            ->where('is_verified', true) //Verified by CPDMO
            ->select(
                'cpd_schedule_courses.schedule_course_id',
                'cpd_course_kh as activity_name',
                'cpd_course_code',
                'credits',
                DB::raw("DATE_FORMAT(verified_date, '%d/%m/%Y') As verified_date_str")
            )
            ->orderBy('verified_date', 'DESC')
            ->get();

        $message = $lang == 'en' ? config('constants.messages_en.request_success') : config('constants.messages.request_success');
        return ApiResponse::success($message, config('constants.codes.success'), $data);
    }

    public function getCreditsNumber($payroll_id)
    {
        $data = EnrollmentCourse::join('cpd_schedule_courses', 'cpd_schedule_courses.schedule_course_id', '=', 'cpd_enrollment_courses.schedule_course_id')
            ->join('cpd_courses as t2', 'cpd_schedule_courses.cpd_course_id', '=', 't2.cpd_course_id')
            ->where('payroll_id', $payroll_id)
            ->where('confirm_completed', 1)
            ->where('is_verified', true)
            ->sum('credits');

        return ApiResponse::success('', config('constants.codes.success'), (int)$data);
    }

    public function registerCPDActivity(Request $request)
    {
        $request->validate([
            'schedule_course_id' => 'required',
            'payroll_id' => 'required',
            'provider_id' => 'required',
            'enroll_option' => 'required',
            'lang' => 'required'
        ]);
        $lang = $request->lang;

        //Check expired Registration
        $expired_cpd = ScheduleCourse::where('schedule_course_id', $request->schedule_course_id)
            ->select(DB::raw("(CASE WHEN curdate() BETWEEN reg_start_date AND reg_end_date THEN 0 ELSE 1 END) As is_expired"))->first();
        if (!empty($expired_cpd) && $expired_cpd->is_expired == 1) {
            $message = $lang == 'en' ? 'The registration period is close' : 'ការចុះឈ្មោះវគ្គនេះបានអស់សុពលភាព';
            return ApiResponse::error($message, config('constants.codes.fail_403'), 403);
        }

        //Check if staff already registered
        $old_enroll = EnrollmentCourse::where('schedule_course_id', $request->schedule_course_id)
            ->where('payroll_id', $request->payroll_id)->first();
        if (!empty($old_enroll)) {
            if ($old_enroll->enroll_status_id == CpdStatus::UN_REGISTERED) {
                $update_row = array();
                $update_row['enroll_status_id'] = CpdStatus::REGISTERED;
                $update_row['staff_check_status'] = CheckStatus::REGISTERED;
                $update_row['enroll_date'] = now();
                $update_row['staff_check_date'] = now();
                $old_enroll->update($update_row);
                $old_enroll->refresh();
                $message = $lang == 'en' ? 'You have successfully requested to register this CPD offering.' : 'អ្នកបានស្នើរជោគជ័យការចុះឈ្មោះសកម្មភាពCPDនេះ។';
                return ApiResponse::success($message, null, $old_enroll);
            } else {
                $message = $lang == 'en' ? 'You have previously registered this CPD offering already!' : 'អ្នកបានចុះឈ្មោះការផ្តល់ជូនវគ្គអវបនេះពីមុនរួចហើយ!';
                return ApiResponse::error($message);
            }
        }

        $enroll = EnrollmentCourse::create([
            'schedule_course_id'    => $request->schedule_course_id,
            'payroll_id'            => $request->payroll_id,
            'enroll_option'         => $request->enroll_option,
            'provider_id'           => $request->provider_id,
            'enroll_status_id'      => CpdStatus::REGISTERED,
            'staff_check_status'    => CheckStatus::REGISTERED,
            'staff_check_date'      => now(),
            'enroll_date'           => now(),
            'supervisor_payroll'    => null,
            'supervisor_status'     => CheckStatus::PENDING,
            'supervisor_check_date' => null,
            'provider_status'       => CheckStatus::PENDING,
            'provider_check_date'   => null,
            'confirm_completed'     => 0,
            'completed_date'        => null
        ]);
        if ($enroll) {
            $enroll->refresh();
            $message = $lang == 'en' ? 'You have successfully requested to register this CPD offering.' : 'អ្នកបានស្នើរជោគជ័យការចុះឈ្មោះសកម្មភាពCPDនេះ។';
            return ApiResponse::success($message, null, $enroll);
        } else {
            $message = $lang == 'en' ? config('constants.messages_en.create_fail') : config('constants.messages.create_fail');
            return ApiResponse::error($message);
        }
    }

    public function unRegisterCPDActivity($lang, $id, Request $request)
    {
        $enroll = EnrollmentCourse::find($id);
        if (empty($enroll)) {
            return ApiResponse::error('Invalid request. No record found!', config('constants.codes.fail_404'), 404);
        }
        if ($enroll->provider_status == CheckStatus::APPROVED) {
            return ApiResponse::error('This activity has been approved by CPD provider already.', config('constants.codes.fail_403'), 403);
        }

        $update_row = array();
        $update_row['enroll_status_id'] = CpdStatus::UN_REGISTERED;
        $update_row['staff_check_date'] = now();
        $enroll->update($update_row);
        $enroll->refresh(); // Ensures updated values are loaded

        $message = $lang == 'en' ? 'You have successfully unregistered this CPD offering.' : 'អ្នកបានបោះបង់ការចុះឈ្មោះសកម្មភាពCPDនេះ។';

        return ApiResponse::success($message, null, $enroll);
    }
    //End Secure APIs

}
