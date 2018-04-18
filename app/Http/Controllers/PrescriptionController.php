<?php

namespace App\Http\Controllers;
use Auth;
use App\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
  // /**
//   to register and get an user name and a password in order to access the online services;  -done
// to login  -done
// to check the GP or nurse’s availability on a specific day;   -done
// to check all the GPs and nurses on duty on a specific day;  -done
// to book, change and cancel an appointment;  - done
// to extend the prescriptions; the user should have access to the list of medications and be able to  extend just those that can be extended.  
// To check the results of tests they have done.  
// to chat online with a receptionist. 
// The same system is used by the receptionists that should be able to:
//
// check the calendar of appointments (per day, week, month) ;
// book, change and cancel an appointment for a patient;
//  Develop the architecture & build a skeleton for the deployment of an integrated internet application to support the Over Surgery.  
  // */
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $prescriptions = Prescription::where('patient_id', '=', Auth::user()->id)->get();

        // return $prescriptions;
        return view('prescription.view', [ 'prescriptions' => $prescriptions]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prescriptions = Prescription::where('patient_id', '=', Auth::user()->id)->
        where('id', '=', $id)->first();

        return view('prescription.show', [ 'prescription' => $prescriptions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Prescription $prescription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prescription $prescription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription)
    {
        //
    }
}
