@extends('layouts.app')

@section('content')

<div class="container">
  <div class="row">

    <div class="col-md-9">
    @foreach($prescriptions as $prescription)
      <div class="card mb-3">
        <a href="/prescriptions/show/{{$prescription->id}}">
        <div class="card-body">
        <h4>{{ $prescription->name}} by {{ $prescription->staff->getfullName() }}</h4>


    </div>
  </a>
      </div>
    @endforeach
    </div>
  </div>
</div>

@endsection
