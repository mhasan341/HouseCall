@extends('layouts.admin')
@section('content')
<div class="container">
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

                        <div class="d-flex justify-content-around">
                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole('admin'))
                            <!-- Total Users Card -->
                            <div class="col-md-4">
                                <div class="card shadow-lg" style="border-left: 5px solid #007bff;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-primary text-bold">Total Users</h5>
                                                <h3 class="card-text">{{ $totalUsers }}</h3>

                                            </div>
                                            <div>
                                                <i class="fas fa-users fa-3x text-primary"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- Total Drugs Card -->
                            <div class="col-md-4">
                                <div class="card shadow-lg" style="border-left: 5px solid #28a745;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="card-title text-success text-bold">Total Drugs Saved</h5>
                                                <h3 class="card-text">{{ $totalDrugs }}</h3>

                                            </div>
                                            <div>
                                                <i class="fas fa-pills fa-3x text-success"></i>
                                            </div>
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