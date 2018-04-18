@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">

    <div class="col-md-9">
      <div class="card mb-3">
        <div class="card-body">

          @foreach($prescription->drugs as $drug)
            <li> {{$drug->name}} {{$drug->pivot->dosage}}
              @if($drug->pivot->reorder)
                <input name="reorder"/>
              @endif
            </li>
          @endforeach

          <button> Reorder </button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
