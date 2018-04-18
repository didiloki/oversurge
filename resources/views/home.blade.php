@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
            <div class="col">
              <a href="{{ route('appointments') }}">
              <div class="card">
                <div class="card-body text-center">
                  <img src="/img/appointment-book.svg" width="200" height="200"/>
                  <h3 class="text-center">
                    Appointments
                  </h3>
                </div>
              </div>
              </a>
            </div>

            <div class="col">
              <a href="/tests">
              <div class="card">
                <div class="card-body text-center">
                  <img src="/img/appointment-book.svg" width="200" height="200"/>
                  <h3 class="text-center">
                    Test Results
                  </h3>
                </div>
              </div>
              </a>
            </div>


            <div class="col">
              <a href="{{ route('prescriptions') }}">
                <div class="card">
                  <div class="card-body text-center">
                    <img src="/img/prescription.svg" width="200" height="200" />
                    <h3 class="text-center">
                      Prescriptions
                    </h3>
              </div>
              </div>
              </a>
            </div>

            {{--- <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))}
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                   @endif

                    You are logged in!
                </div>
            </div> ---}}

    </div>
</div>
@endsection
