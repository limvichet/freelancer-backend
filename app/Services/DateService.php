<?php

namespace App\Services;

use App\Models\Date;

class DateService
{
    public function all()
    {
        return Date::latest()->get();
    }

    public function find($id)
    {
        return Date::findOrFail($id);
    }

    public function create(array $data)
    {
        return Date::create($data);
    }

    public function update($id, array $data)
    {
        $Date = Date::findOrFail($id);
        $Date->update($data);
        return $Date;
    }

    public function delete($id)
    {
        Date::findOrFail($id)->delete();
    }
}
