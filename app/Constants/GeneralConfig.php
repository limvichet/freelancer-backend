<?php

namespace App\Constants;

class GeneralConfig
{
    public const TOKEN_EXPIRE_TIME = 1440;//minutes​ in one day
    public const MAX_ATTEMPTS = 5;
    public const LOCKOUT_TIME = 10; //minutes
    public const LOCKED_CODE = 429;
}