@extends('base')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
    <div class="col-lg-12 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
            <div class="col-sm-7">
                <div class="card-body">
                <h5 class="card-title text-primary">환영합니다. 닉네임 님! 🎉</h5>
                <p class="mb-4">
                    지금 바로 문제를 만들어 보세요.
                </p>
                <a href="#" class="btn btn-sm btn-outline-primary">문제 만들기</a>
                </div>
            </div>
            <div class="col-sm-12 text-center text-sm-left">
                <div class="card-body pb-0 px-0 px-md-4">
                <img src="{{ asset('/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User"
                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                    data-app-light-img="illustrations/man-with-laptop-light.png">
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>
</div>


@endsection()