<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;


class SnapshotFace extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $guarded = [];

    public function snapshot()
    {
        return $this->belongsTo('App\Models\Snapshot');
    }
}
