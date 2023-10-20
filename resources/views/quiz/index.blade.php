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
        @if (!Auth::check()) {{-- 로그인이 안되어 있으면 --}}
            <div class="col-lg-12 mb-2 order-0">
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
        @else {{-- 로그인이 되어있으면 --}}
            <!-- #나의 퀴즈 -->
            <div class="mt-4 mb-2 pb-1 d-flex justify-content-between">
                <h5><i class="fa-solid fa-hashtag">&nbsp;</i>나의 퀴즈 💁‍♂️</h5>
                <a href="#">더보기</a>
            </div>
            @if (!$myQuizs) {{-- 만든 퀴즈가 없으면 --}}
                <div class="col-lg-12 mb-4 order-0">
                    <div class="card">
                        <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                            <h5 class="card-title text-primary">안녕하세요, {{ $nickname }} 님! 🎉</h5>
                            <p>만든 퀴즈가 없어요.<br>지금 바로 첫 번째 퀴즈를 만들어 보세요.</p>
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
            @else {{-- 만든 퀴즈가 있으면 : 만든 퀴즈 목록 --}}
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">안녕하세요, {{ $nickname }}님! 🎉</h5>
                                <a href="{{ url('quiz/create') }}" class="btn btn-sm btn-primary">퀴즈 만들기</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                @foreach ($myQuizs as $myQuiz)
                    <div class="col-md-4">
                        <div class="card mb-3">
                        <div class="row g-0">
                            <div class="col-md-12">
                            <div class="card-body">
                                @if ($myQuiz->secret == "N")
                                    <p class="badge bg-label-primary">공개</p>
                                    @if ($myQuiz->incomplete == "Y")
                                        <span class="badge bg-label-danger">미완성</span>
                                    @endif
                                @elseif ($myQuiz->secret == "Y")
                                    <p class="badge bg-label-warning">비공개</p>
                                    @if ($myQuiz->incomplete == "Y")
                                        <span class="badge bg-label-danger">미완성</span>
                                    @endif                                    
                                @endif
                                <h5 class="card-title">{{ $myQuiz->name }}</h5>
                                <p class="card-text">{{ $myQuiz->subject }}</p>
                                <p class="card-text"><small class="text-muted">{{ $myQuiz->created_at->diffForHumans() }}</small></p>
                                <div class="text-end">
                                    <a class="btn btn-primary" href="{{ url('quiz/solve/' . $myQuiz->id . "/type1") }}">풀기</a>
                                    <a class="btn btn-secondary" href="{{ url('quiz/' . $myQuiz->id . '/edit') }}">수정</a>
                                    <a class="btn btn-danger" href="{{ url('quiz/' . $myQuiz->id . '/edit') }}">삭제</a>
                                    {{-- <div class="btn-group dropup">
                                        <button type="button" class="btn btn-primary dropdown-toggle show" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                          풀기
                                        </button>
                                        <ul class="dropdown-menu" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate(0px, -42px);" data-popper-placement="top-start">
                                          <li><a class="dropdown-item" onclick="solve1({{ $test->id }})">풀기</a></li>
                                          <li><a class="dropdown-item" onclick="solve2({{ $test->id }})">한 문제씩 풀기</a></li>
                                          <li>
                                            <hr class="dropdown-divider">
                                          </li>
                                          <li><a class="dropdown-item" href="{{ url('quiz/' . $test->id . '/edit') }}">수정</a></li>
                                          <li><a class="dropdown-item" href="javascript:void(0);">삭제</a></li>
                                        </ul>
                                    </div> --}}
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
    {{-- 공통 --}}
    <div class="mt-4 mb-2 pb-1 d-flex justify-content-between">
        <h5 class=""><i class="fa-solid fa-hashtag">&nbsp;</i>공개 퀴즈 🌏</h5>
        <a href="#">더보기</a>
    </div>    
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <tbody>
                    @foreach ($quizs as $quiz)
                        <tr>
                            <td>
                                <a href="{{ url('quiz/solve/' . $quiz->id . "/type1") }}"><span style="font-size: 17px;">{{ $quiz->name }}</span></a><br>
                                <i class="fa-solid fa-circle-user"></i>&nbsp;{{ $quiz->uid }}&nbsp;&nbsp;|&nbsp;&nbsp;
                                조회: ?? &nbsp;&nbsp;|&nbsp;&nbsp;
                                {{ $quiz->created_at->diffForHumans() }}
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function solve1(testID) {
      // confirm 창을 띄우고 사용자의 선택을 확인합니다.
      var isConfirmed = confirm("일반적인 시험처럼 모든 문제가 표시되고 정답과 결과는 마지막에 표시됩니다.");

      // 사용자가 확인을 눌렀을 때
      if (isConfirmed) {
        var url = "{{ url('quiz/solve') }}/" + testID + "/type1";
        window.location.href = url;
      } else {
        return;
      }
    }

    function solve2(testID) {
      // confirm 창을 띄우고 사용자의 선택을 확인합니다.
      var isConfirmed = confirm("한 문제씩 표시되고 정답과 결과를 바로 확인할 수 있습니다.");

      // 사용자가 확인을 눌렀을 때
      if (isConfirmed) {
        window.location.href = "#";
      } else {
        return;
      }
    }    
</script>

@endsection()