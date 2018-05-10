<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function getImage()
    {
        if(isset($this->snapshot))
        {
            return $this->snapshot->getFirstMediaUrl('images');
        } else {
            return $this->getEventImageType();
        }
    }

    public function getEventImageType()
    {
        switch ($this->type){
            case 'face-detected':
                return '/images/camera.png';
                break;
            case 'authentication-attempt':
                return '/images/lock.png';
                break;
            case 'iot-action':
                return '/images/lightning.png';
                break;
            default:
                return '/images/questionmark.png';
                break;
        }

    }


    public function snapshot()
    {
        return $this->belongsTo('App\Models\Snapshot');
    }
}
