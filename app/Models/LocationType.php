<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    use HasFactory;

    protected $table = 'sys_location_types';

    protected $primaryKey = 'location_type_id';

    public $timestamps = false;

    protected $fillable = ['location_type_id', 'location_type_kh', 'location_type_en', 'under_moeys', 'is_school', 'level_id'];

    const MoEYs = 1;
    const GD = 2;
    const University = 3;
    const Department = 4;
    const Institute = 5;
    const RTTC = 6;
    const PTTC = 7;
    const PreTTC = 8;
    const PracticalHighSchool = 9;
    const PracticalSecondarySchool = 10;
    const PracticalPrimarySchool = 11;
    const POE = 12;
    const DOE = 13;
    const HighSchool = 14;
    const SecondarySchool = 15;
    const CES = 16;
    const PrimarySchool = 17;
    const PreSchool = 18;
    const IslamSchool = 19;

    const LevelMoeys = 1;
    const LevelDept = 2;
    const LevelPoe = 3;
    const LevelDoe = 4;
    const LevelSchool = 5;

    public function getLocationTypeNameAttribute() {
        return $this->{'location_type_'.app()->getLocale()};
    }
}
