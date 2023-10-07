@extends('base')

@section('content')

<style>
    .btn-white {
        color: #696cff;
        background-color: #fff;
        border-color: #696cff;
        box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, 0.4);
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        @if (!Auth::check()) <!-- 로그인이 안되어 있으면 -->
            <div class="col-lg-12 mb-5 order-0">
                <div class="card bg-primary text-white mb-3">
                    <div class="card-header">Welcome!</div>
                    <div class="card-body">
                        <h5 class="card-title text-white">SmartQuiz는 빠르고 편한 퀴즈솔루션 입니다.</h5>
                        <p class="card-text">지금 바로 로그인하고 모든 서비스를 이용해 보세요.</p>
                        <a href="{{ url('login')}}" class="btn btn-white">로그인&nbsp;<i class="fa-solid fa-arrow-right"></i></a>
                        <a href="{{ url('register')}}" class="btn btn-white">회원가입&nbsp;<i class="fa-solid fa-user-plus"></i></a>
                        <div class="text-center">
                            <img src="{{ asset('/assets/img/illustrations/login.png') }}" height="200" alt="View Badge User">
                        </div>
                    </div>
                </div>
            </div>
        @else <!-- 로그인이 되어있으면 -->
            <!-- 나의 퀴즈 -->
            <h5 class="mt-4 pb-1 mb-4"><i class="fa-solid fa-hashtag">&nbsp;</i>나의 퀴즈 💁‍♂️</h5>
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                        <h5 class="card-title text-primary">안녕하세요, 닉네임님! 🎉</h5>
                        <p>아직 퀴즈를 만든 적이 없어요. 😥<br>지금 바로 첫 번째 퀴즈를 만들어 보세요.</p>
                        <a href="{{ url('quiz/create') }}" class="btn btn-outline-primary">퀴즈 만들기</a>
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
        @endif
    </div>
</div>

@endsection()