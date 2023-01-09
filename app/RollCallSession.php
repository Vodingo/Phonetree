<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RollCallSession extends Model
{
    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at', 'active'];

    protected $with = ['createdBy', 'updatedBy'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function active()
    {
        try {
            $sessions = RollCallSession::where('active', '1')->where('deleted', '0')->orderBy('created_at', 'DESC')->get();

            $result = [];

            foreach ($sessions as $session) {

                $result[] = array(
                    'id' => $session->id,
                    'description' => $session->description . ' - ' . Carbon::parse($session->incident_date)->format('d M Y'),
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('RollCallSession.php -> active : ' .$ex->getMessage());
        }

    }

    public static function getAllSessions()
    {
        try {
            $sessions = RollCallSession::orderBy('created_at', 'DESC')->where('deleted', '0')->get();

            $result = [];

            foreach ($sessions as $session) {

                $result[] = array(
                    'id' => $session->id,
                    'description' => $session->description,
                    'incident_date' => Carbon::parse($session->incident_date)->format('d M Y'),
                    'created_by' => (!empty($session->createdBy)) ? $session->createdBy->name : '',
                    'created_at' => Carbon::parse($session->created_at)->format('d M Y H:i:s'),
                    'status' => $session->active ? 'Active' : 'Completed',
                    'updated_by' => (!empty($session->updatedBy)) ? $session->updatedBy->name : '',
                    'updated_at' => Carbon::parse($session->created_at)->format('d M Y H:i:s'),
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('RollCallSession.php -> getAllSessions : ' .$ex->getMessage());
        }

    }

    public static function filterSessions($filters)
    {
        try {
            $session = new RollCallSession();

            $session = $session->newQuery();

            if (isset($filters['description'])) {
                $session->where('description', 'LIKE', "%{$filters['description']}%");
            }

            if ($filters['filterByDate']) {
                $session->whereBetween('incident_date', [$filters['startDate'], $filters['endDate']]);
            }

            $sessions = $session->get();

            $result = [];

            foreach ($sessions as $session) {

                $result[] = array(
                    'id' => $session->id,
                    'description' => $session->description,
                    'incident_date' => Carbon::parse($session->incident_date)->format('d M Y'),
                    'created_by' => (!empty($session->createdBy)) ? $session->createdBy->name : '',
                    'created_at' => Carbon::parse($session->created_at)->format('d M Y H:i:s'),
                    'status' => $session->active ? 'Active' : 'Completed',
                    'updated_by' => (!empty($session->updatedBy)) ? $session->updatedBy->name : '',
                    'updated_at' => Carbon::parse($session->created_at)->format('d M Y H:i:s'),
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('RollCallSession.php -> filterSessions : ' .$ex->getMessage());
        }

    }

    public function getHomeRollCallSessionSelectList()
    {
        try {
            $sessions = RollCallSession::where('active', '1')->where('deleted', '0')->orderBy('created_at', 'DESC')->get();

            $result = [];

            foreach ($sessions as $session) {

                $result[] = array(
                    'id' => $session->id,
                    'description' => $session->description . ' - ' . Carbon::parse($session->incident_date)->format('d M Y'),
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('RollCallSession.php -> getHomeRollCallSessionSelectList : ' .$ex->getMessage());
        }

    }

    public function getDashboardRollCallSessionSelectList()
    {
        try {
            $sessions = RollCallSession::where('deleted', '0')->orderBy('created_at', 'DESC')->get();

            $result = [];

            foreach ($sessions as $session) {

                $result[] = array(
                    'id' => $session->id,
                    'description' => $session->description . ' - ' . Carbon::parse($session->incident_date)->format('d M Y'),
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('RollCallSession.php -> getHomeRollCallSessionSelectList : ' .$ex->getMessage());
        }

    }
}
