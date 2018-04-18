@extends('layouts.app')

@section('styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.print.css"/>
@endsection

@section('content')

<div class="container">
  <div class="row">

    <div class="col-md-6 list_appointments">
    @foreach($appointments as $appointment)
      <div class="card mb-3">
        <div class="card-body">
          You have an appointment at:

        <i>{{ Carbon\Carbon::parse($appointment->start_time)->format('d-m-Y, h:i') }}</i>
        <div> with
          {{ $appointment->staff->firstname }} {{ $appointment->staff->lastname }}
        </div>
        <a data-value="{{$appointment->id}}" class="btn btn-warning modify_this" href=""> modify</a> <a href="#" data-value="{{ $appointment->id }}" class="cancel_this btn btn-danger">Cancel</a>
    </div>
      </div>
    @endforeach
    </div>

    <div class="col-md-6 booking_info">
      <a class="btn btn-block book_start" href="#">Start Appointment Booking</a>
      <div id="doctor_ava">
        <select id="staffs" class="form-control mb-3">
          <option value="0" data-id="0"> Select Staff </option>
          @foreach($staffs as $staff)
            <option value="{{ $staff->id}}" data-id="{{ $staff->id}}"> {{ $staff->getfullName()}} </option>
          @endforeach
        </select>
      </div>

      <div id="date_slots" class="mb-3 btn-group-toggle" data-toggle="buttons">
        <h3>Date Slot</h3>

      </div>

      <div id="time_slots" class="mb-3 btn-group btn-group-toggle" data-toggle="buttons">
        <h3>Time Slot</h3>

      </div>

      <div id="cancel_slots" class="mb-3">
        <h3>Cancel Slot</h3>
        <textarea class="form-control"></textarea>
      </div>

      <button disabled class="modify btn btn-block btn-danger">
        Modify Appointment
      </button>
      <div class="submit_holder">
      <button disabled class="submit btn btn-block btn-primary">
        Book Appointment
      </button>
    </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>


<script>
let staff
let date
let patient = {{Auth::id()}}
let cancel_id
let appoinment_id
let type_action

$('.list_appointments').on('click','.cancel_this', function(e){
  e.preventDefault()
  let this_el = $(this).parent().parent()
  cancel_id = $(this).attr('data-value')
  $.ajax({
      url:`/api/appointment/cancel/${cancel_id}`,
      method:'delete',
      success:function(data){
        this_el.fadeOut('slow');
      }
    })
})


$('.list_appointments').on('click','.modify_this', function(e){
  e.preventDefault();
  appoinment_id = $(this).attr('data-value')
  type_action = 1
  $('#doctor_ava').fadeIn('slow');
  $('.submit_holder').hide();
  $('.modify').fadeIn('slow');

  // $.ajax({
  //     url:`/api/appointment/modify/${cancel_id}`,
  //     method:'delete',
  //     success:function(data){
  //       this_el.fadeOut('slow');
  //     }
  //   })


})

$('.book_start').on('click',function(e){
    e.preventDefault();
    type_action = 0
    $('#doctor_ava').fadeIn('slow');
    $('.submit_holder').show();
    $('.modify').hide();
})

$('#staffs').on('change', function(e){

      $('.alert').remove()
      $('#date_slots').empty()
      $('#time_slots').empty()
      $('#date_slots').fadeIn('slow');

      staff = this.value;

      $.ajax({
        url: `/api/appointment/date/avalibility`,
        method:'GET',
        success: function(data){
          data.map(function(d){
            $('#date_slots').append(`<label data-value="${d}" class=\"btn btn-secondary date_slot\"><input type=\"radio\" name=\"options\" autocomplete=\"off\" value="${d}">${d}</label>`)
          })
        }
      })
})

$('#date_slots').on('click','.date_slot',function(e){
    e.preventDefault();
    $('#time_slots').empty()

    $('#time_slots').fadeIn('slow');
    date = $(this).attr('data-value')

    $.ajax({
      url:`/api/staff/${staff}/avalibility/${date}`,
      success:function(data){
        console.log(data)
          if(data.length > 0){
            data.map(function(d){
                $('#time_slots')
                .append(`<label data-value="${d.fulldate}" class="btn btn-secondary time_slot"><input type="radio" name="options" autocomplete="off" value="${d.fulldate}">${d.time}</label>`)
            })

            if(type_action === 0){
              $(".submit").removeAttr("disabled")
            }else{
              $(".modify").removeAttr("disabled")
            }
            // $(".submit").removeAttr("disabled")
        }else{
          $('#time_slots')
          .append(`<div class="alert alert-danger"> There are not slots</div>`)
        }

      }
    });

})

$('#time_slots').on('click', ".time_slot", function(e){
    console.log($(this).attr('data-value'))
    date = $(this).attr('data-value')
})


$('.modify').on('click', function(e){
  e.preventDefault();
  alert(appoinment_id + " ")

  $.ajax({
    method:'POST',
    url:`/api/appointment/modify/${appoinment_id}`,
    data: { staff: staff, date: date, patient: patient},
    success:function(data){
      $('.booking_info')
      .prepend(`<div class="alert alert-success"> Appointment Modified!</div>`)



    }

  })
})


$(".submit").on('click', function(e){

  $.ajax({
    method:'POST',
    url:'/api/appointment',
    data: { staff: staff, date: date, patient: patient},
    success:function(data){
      $('.booking_info')
      .prepend(`<div class="alert alert-success"> Appointment Booked!</div>`)



    }

  })
})
</script>
@endsection
