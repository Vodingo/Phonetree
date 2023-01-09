<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function getHomeRegisterStatusSelectList()
    {
        try {

            $statuses = $this::where('active', '1')->get(['id as status_id', 'name as status_description']);
            return $statuses;

        } catch (\Exception $ex) {

            dd('Status.php -> getHomeRegisterStatusSelectList : ' .$ex->getMessage());

        }

    }
}
