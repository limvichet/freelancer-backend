<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Province;

class AddressController extends Controller
{
    //
    public function __construct()
    {
    	//$this->middleware('auth');
    }

    public function getProvinceOptions($lang)
    {
        $key_order = $lang == 'en' ? 'name_en' : 'name_kh';

        $options = Province::active()->select('pro_code as id', "{$key_order} as value")
            ->orderBy('pro_code')
            ->get();
        
        return ApiResponse::success('', null, $options);
    }
}
