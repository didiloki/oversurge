<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'start_time', 'end_time', 'description',
        'staff_id', 'patient_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function staff(){
      return $this->belongsTo('App\User', 'staff_id', 'id');;
    }

    public function drugs(){
      return $this->belongsToMany('App\Drug')->withPivot('dosage', 'reorder');
    }

}
