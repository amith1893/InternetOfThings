<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Snapshot extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];

    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }

    public function snapshotFace()
    {
        return $this->hasMany('App\Models\SnapshotFace');
    }
}
