<?php

return [
    'codes' => [
        'success'   => 200, //Success Requst
        'fail_400'  => 400, //Bad Request
        'fail_401'  => 401, // Unauthorized
        'fail_403'  => 403, // Forbidden
        'fail_404'  => 404, // Not Found
        'fail_500'  => 500, // 500 Internal Server Error
    ],

    'messages' => [
        'login_success'     => 'ការស្នើសុំចូលប្រព័ន្ធបានសម្រេច។',
        'login_fail'        => 'ឈ្មោះគណនីរឺលេខកូដសម្ងាត់មិនត្រឹមត្រូវ!',
        'register_success'  => 'ការស្នើសុំបង្កើតគណនីថ្មីបានសម្រេច ប៉ុន្តែត្រូវរង់ចាំការយល់ព្រមពីប្រធានរបស់អ្នកសិន។',
        'register_fail'     => 'ការបង្កើតគណនីថ្មីត្រូវបានបរាជ័យ!',
        'create_success'    => 'ទិន្នន័យត្រូវបានបង្កើតចូលប្រព័ន្ធបានសម្រេច។',
        'create_fail'       => 'បង្កើតទិន្នន័យថ្មីមិនបានសម្រេច!',
        'request_success'   => 'ការស្នើសុំទទួលបានជោគជ័យ។',
        'request_fail'      => 'ការស្នើសុំមិនបានសម្រេច!',
        'no_data'           => 'មិនមានទិន្នន័យ!'
    ],

    'messages_en' => [
        'login_success'     => 'Login successfully.',
        'login_fail'        => 'Incorrect username or password!',
        'register_success'  => 'Your registration request was successfully created, but it is still pending for approval from your manager.',
        'register_fail'     => 'Your registration request failed!',
        'create_success'    => 'Data is successfully stored into the system.',
        'create_fail'       => 'Failed to create new data!',
        'request_success'   => 'Data successful requested.',
        'request_fail'      => 'Failed to request the data!',
        'no_data'           => 'No record found!'
    ],

    'CONST_PENDING' => 'pending',
    'CONST_APPROVED' => 'approved',
    'CONST_VERIFIED' => 'verified',
    'CONST_REJECTED' => 'rejected',
    'CONST_HRMIS_REG_TYPE' => 1,
    'CONST_MOBILE_REG_TYPE' => 2,
    'CONST_CPD_REG_TYPE' => 3,
];