<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;
use App\User;
use App\Schedule;
use App\Appointment;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

          // find doctor
          // show all available 5 days from today
          // get all available time range
          // $daysOfWeek = array("Mon", "Tues", "Weds", "Thurs", "Fri");
          // //find staff schedules only
          date_default_timezone_set('Africa/Lagos');
          $weekNum = (int)date('w');
          // echo date("h:i")."<br/>";
          $user = User::with('schedules')->get()->find(2);
          echo $user;
          echo "<br/>";echo "<br/>";echo "<br/>";
          echo date("Y-m-d") . " ".$user->schedules[$weekNum-1]->time_from. " - ";
          echo date("Y-m-d")." ".$user->schedules[$weekNum-1]->time_to;
          echo "<br/>";echo "<br/>";echo "<br/>";

          $schedule_start = "";
          $schedule_end = date("Y-m-d") . " ".$user->schedules[$weekNum-1]->time_to;

          if(date('H:i') > $user->schedules[$weekNum-1]->time_from){
            $current_time = "";
            $current_time = (date('i')< 30)? date('H').":30" : date('H',strtotime('+1 hours')).":00";

            echo "ok<br/>";
            if($current_time > date('H:i')){

              $schedule_start = date("Y-m-d", strtotime('+1 day')) ." ". $user->schedules[$weekNum]->time_from;
              $schedule_end = date("Y-m-d",strtotime('+1 day')) . " ".$user->schedules[$weekNum-1]->time_to;

            }else{
              $schedule_start = date("Y-m-d") ." ". $current_time;
            }


          }else{
            $schedule_start = date("Y-m-d") . " ".$user->schedules[$weekNum-1]->time_from;
          }
          echo "the start time is " . $schedule_end . "<br/>";



          // // return $user->schedules[0]->time_to;

          // // show all available 5 days from today
          //
          // $startTime = Carbon::now();
          // $endTimes = Carbon::now()->addWeekdays(5);
          //
          // $date = new DateTime(date("Y-m-d H:i:s"));
          // $date->modify('+1 day');
          // // $date->format('Y-m-d');
          // // new DateTime($date->format('Y-m-d')." ".
          // echo $endTime = $user->schedules[$weekNum-1]->time_to;
          // echo "<br/>";
          //
          // // gettimeofday()
          // $starttime = date("h:i");  // your start time
          // $endtime = $endTime;  // End time
          // $duration = '30';  // split by 30 mins
          //
          // $array_of_time = array ();
          // $start_time    = strtotime ($starttime); //change to strtotime
          // $end_time      = strtotime ($endtime); //change to strtotime
          //
          // $add_mins  = $duration * 60;
          //
          // while ($start_time <= $end_time) // loop between time
          // {
          //    $array_of_time[] = date ("h:i", $start_time);
          //    $start_time += $add_mins; // to check endtie=me
          // }
          // print_r($array_of_time);
          // return $this->createDateRange($startTime, $endTimes);


          // $schedule = [
          //         'start' => '2015-11-18 09:00:00',
          //         'end' => '2015-11-18 17:00:00',
          //     ];

              $start = Carbon::instance(new DateTime($schedule_start));
              $end = Carbon::instance(new DateTime($schedule_end));

              $appointments = Appointment::where("staff_id","=", 2)->get();

              echo $appointments ."<br/>";
                echo "<br/>";echo "<br/>";echo "<br/>";


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
                      echo $slot->toDateTimeString();// . ' to ' . $to->toDateTimeString();
                      echo ' is available';
                      echo '<br />';
                                        // echo '<br />';
                  }

                  // echo '<br />';
              }
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
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        //
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


}
