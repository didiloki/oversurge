<?php

namespace App\Http\Controllers;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;
use App\Appointment;
use App\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth',['except' => ['staff_avalibility','store', 'cancel','staff_date','update']]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $appointments = Appointment::where('patient_id', '=', Auth::user()->id)->where('status', '=', 1)->orderBy('start_time', 'desc')->get();

        $staffs = User::where("role", "=", 2)->get();

        return view('appointment.view')->with('appointments', $appointments)
              ->with('staffs', $staffs);
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

        $data = new Appointment();
        $data->staff_id = $request->staff;
        $data->patient_id = $request->patient;
        $data->start_time = $request->date;
        $data->end_time = Carbon::instance(new DateTime($request->date))->addMinutes(30)->toDateTimeString();

        $data->save();

        // $s = DateTime::createFromFormat('d/m/Y H:i', '15/09/2015 12:00');
        return response()->json([
            'success' => 'Posted',
        ], 200);//$request->all();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        $appointment->start_time = $request->date;
        $appointment->end_time = Carbon::instance(new DateTime($request->date))->addMinutes(30)->toDateTimeString();

        $appointment->update();

        return response()->json([
            'success' => 'Posted',
        ], 200);//$request->all();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        return $appointment;

    }

    public function cancel($id)
    {
        $appointment = Appointment::find($id);
        $appointment->status = 0;

        $appointment->update();
        return $appointment;

    }

    public function staff_date(){
      $startTime = Carbon::now();
      $endTimes = Carbon::now()->addWeekdays(4);


      return $this->createDateRange($startTime, $endTimes);
    }

    public function staff_avalibility($id, $date){

      $time_slots = array();


      date_default_timezone_set('Africa/Lagos');
      $weekNum = (int)date('w');
      if($weekNum < 1) { $weekNum += 1;}
      // return $weekNum;
      $user = User::with('schedules')->get()->find($id);

      $schedule_start = "";
      $schedule_end = $date . " ".$user->schedules[$weekNum-1]->time_to;

      if($date > date('Y-m-d')){

        $schedule_start = $date . " ".$user->schedules[$weekNum-1]->time_from;

      }else if(date('H:i:s') > $user->schedules[$weekNum-1]->time_from){

        $current_time = (date('i')< 30)? date('H').":30:00" : date('H',strtotime('+1 hours')).":00:00";
        $schedule_start = $date ." ". $current_time;

      }else{
        $schedule_start = $date . " ".$user->schedules[$weekNum-1]->time_from;
      }

      $start = Carbon::instance(new DateTime($schedule_start));
      $end = Carbon::instance(new DateTime($schedule_end));


      $appointments = Appointment::where("staff_id","=", $id)
                      ->where('status', '=', 1)->get();


      $minSlotHours = 0;
      $minSlotMinutes = 30;
      $minInterval = CarbonInterval::hour($minSlotHours)->minutes($minSlotMinutes);

      $reqSlotHours = 0;
      $reqSlotMinutes = 30;
      $reqInterval = CarbonInterval::hour($reqSlotHours)->minutes($reqSlotMinutes);



      foreach(new \DatePeriod($start, $minInterval, $end) as $slot){
          $to = $slot->copy()->add($reqInterval);

          // echo $slot->toDateTimeString() . ' to ' . $to->toDateTimeString();

          if($this->slotAvailable($slot, $to, $appointments)){
            $dateObj = new \stdClass(); //create object class
            $split = explode(" ",$slot->toDateTimeString());
            $dateObj->time = $split[1];
            $dateObj->fulldate =  $slot->toDateTimeString();

                array_push($time_slots, $dateObj);

          }

      }
      return $time_slots;
    }

    private function slotAvailable($from, $to, $events){
        foreach($events as $event){
            $eventStart = Carbon::instance(new DateTime($event['start_time']));
            $eventEnd = Carbon::instance(new DateTime($event['end_time']));

            if($from->between($eventStart, $eventEnd) && $to->between($eventStart, $eventEnd)){
                return false;
            }
        }
        return true;
    }

    private function createDateRange($date_time_from, $date_time_to)
    {
        $dates = [];

        while ($date_time_from->lte($date_time_to)) {

            $dates[] = $date_time_from->copy()->format('Y-m-d');

            $date_time_from->addWeekdays(1);
        }

        return $dates;
    }
}
