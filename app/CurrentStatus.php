<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentStatus extends Model
{
    protected $dates = ['DateFU', 'NextFU', 'RecordDate'];
    public static function getStatus($hn)
    {
        return static::where('HN', $hn)->orderBy('RecordDate', 'DESC')->first();
    }

    public function getStatusThai()
    {
        return strtolower($this->SirirajStatus) == 'active' ? 'ส่งเลือดได้' : 'ไม่สามารถส่งเลือดได้';
    }
}
