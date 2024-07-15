@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <!-- Total Users Card -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Total Users</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalUsers }}</h5>
                                    <p class="card-text">Total number of users in the database.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Total Drugs Card -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Total Drugs Saved</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalDrugs }}</h5>
                                    <p class="card-text">Total number of drugs saved by you.</p>
                                </div>
                            </div>
                        </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection