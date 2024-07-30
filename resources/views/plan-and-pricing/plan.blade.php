@extends('layouts.app')

@section('content')
    @php
        dump($plans);
    @endphp
    <div class="container">
        <div class="row gap-3">
            @foreach ($plans as $plan)
                <div class="card mb-2 col-md-4">
                    <div class="card-header">
                        {{ $plan->name }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">${{ $plan->price }}</h5>
                        <p class="card-text">{{ $plan->description }}</p>
                        <a href="{{ URL::tokenRoute('billing', ['plan' => $plan->id]) }}" class="btn btn-outline-primary">
                            Upgrade
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

{{-- 
https://admin.shopify.com/store/kdsfklsdjfklsdajflsdajflksdjf/apps/
7e0c2f0ee1424dcb46ed5d003c6df130/authenticate/token
?shop=kdsfklsdjfklsdajflsdajflksdjf.myshopify.com
&target=%2F%3Fhost%3DYWRtaW4uc2hvcGlmeS5jb20vc3RvcmUva2RzZmtsc2RqZmtsc2RhamZsc2RhamZsa3NkamY
&host=YWRtaW4uc2hvcGlmeS5jb20vc3RvcmUva2RzZmtsc2RqZmtsc2RhamZsc2RhamZsa3NkamY 
--}}
