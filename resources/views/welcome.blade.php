@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($charges as $charge)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            {{ $charge->name }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${{ $charge->price }}</h5>
                            <p class="card-text">{{ $charge->description }}</p>
                            <p>Plan: {{ $charge->plan->name }}</p>
                            <p>Plan: {{ $charge->status }}</p>
                            <p>Charge Type: {{ $charge->type }}</p>
                            <p>Created At: {{ $charge->created_at }}</p>
                            <p>Updated At: {{ $charge->updated_at }}</p>
                            <p>Test: {{ $charge->test }}</p>
                            <p>Activated On: {{ $charge->activated_on }}</p>
                            <p>Cancelled On: {{ $charge->cancelled_on }}</p>
                            <p>Trial Ends On: {{ $charge->trial_ends_on }}</p>
                            <a href="{{ URL::tokenRoute('app.billing-view') }}" class="btn btn-outline-primary">
                                Upgrade
                            </a>
                            @php
                                // dump($charge);
                            @endphp
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

