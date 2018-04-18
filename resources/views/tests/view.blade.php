@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

      <div class="col-md-12">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Test Date</th>
              <th scope="col">Name</th>
              <th scope="col">Result</th>
              <th scope="col">recieved</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tests as $test)
            <tr>
              <td scope="row">{{$test->created_at}}</td>
              <td>{{$test->name}}</td>
              <td>{{$test->result}}</td>
              <td>{{$test->updated_at}}</td>
            </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
</div>

@endsection
