<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'start_time', 'end_time', 'description',
      'medical_id', 'patient_id'

  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];


  /**
     * Get the patients.
     */
    public function patient()
    {
        return $this->belongsTo('App\User', 'patient_id', 'id');
    }

    public function staff()
    {
        return $this->belongsTo('App\User', 'staff_id', 'id');
    }
}
