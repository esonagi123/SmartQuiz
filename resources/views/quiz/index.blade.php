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

@if (\Session::has('userDestroyComplete'))
  <script>
    alert("{!! \Session::get('userDestroyComplete') !!}");;
  </script>
@endif

<div id="noLoginModal"></div>

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
                <a href="{{ url('/quiz/myQuiz') }}">더보기</a>
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
                                    <p class="badge bg-label-secondary">비공개</p>
                                    @if ($myQuiz->incomplete == "Y")
                                        <span class="badge bg-label-danger">미완성</span>
                                    @endif                                    
                                @endif
                                <h5 class="card-title">{{ $myQuiz->name }}</h5>
                                <p class="card-text">{{ $myQuiz->subject }}</p>
                                <p class="card-text"><small class="text-muted">{{ $myQuiz->updated_at->diffForHumans() }} 최종 수정</small></p>
                                <div class="text-end">
                                    @if ($myQuiz->incomplete == "Y")  
                                        <a class="btn btn-primary" href="#" onclick="alert('미완성인 문제입니다.\n수정을 눌러 부족한 부분을 채워주세요.')">풀기</a>
                                    @else
                                        <a class="btn btn-primary" href="{{ url('quiz/solve/' . $myQuiz->id . "/type1") }}">풀기</a>
                                    @endif
                                    <a class="btn btn-secondary" href="{{ url('quiz/' . $myQuiz->id . '/edit') }}">수정</a>
                                    <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete({{ $myQuiz->id }})">삭제</a>
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
    {{-- <div class="mt-4 mb-2 pb-1 d-flex justify-content-between">
        <h5 class=""><i class="fa-solid fa-hashtag">&nbsp;</i>인기 퀴즈 🔥</h5>
        <a href="{{ url('quiz/public') }}"></a>
    </div>    
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <tbody>
                    @foreach ($quizs as $quiz)
                        <tr>
                            <td>
                                @if (!Auth::check())
                                <a href="javascript:void(0);" onclick="confirm1({{ $quiz->id }})">
                                    <span style="font-size: 17px;">{{ $quiz->name }}</span>
                                </a><br>
                                @else
                                <a href="{{ url('quiz/solve/' . $quiz->id . "/type1") }}">
                                    <span style="font-size: 17px;">{{ $quiz->name }}</span>
                                </a><br>
                                @endif
                                <div class="mt-2"></div>                  
                                <img src="{{ asset('/assets/img/avatars/avatar' .  $quiz->avatar .'.png') }}" style="width:25px;">&nbsp;{{ $quiz->nickname }}&nbsp;·&nbsp;
                                조회 : {{ $quiz->viewCount }}&nbsp;·&nbsp;
                                {{ $quiz->updated_at->diffForHumans() }} 수정
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> --}}

    <div class="mt-4 mb-2 pb-1 d-flex justify-content-between">
        <h5 class=""><i class="fa-solid fa-hashtag">&nbsp;</i>공개 퀴즈 🌏</h5>
        <a href="{{ url('quiz/public') }}">더보기</a>
    </div>    
    <div class="card">
        <div class="table-responsive text-nowrap">
            <table class="table">
                <tbody>
                    @foreach ($quizs as $quiz)
                        <tr>
                            <td>
                                @if (!Auth::check())
                                <a href="javascript:void(0);" onclick="confirm1({{ $quiz->id }})">
                                    <span style="font-size: 17px;">{{ $quiz->name }}</span>
                                </a><br>
                                @else
                                <a href="{{ url('quiz/solve/' . $quiz->id . "/type1") }}">
                                    <span style="font-size: 17px;">{{ $quiz->name }}</span>
                                </a><br>
                                @endif
                                <div class="mt-2"></div>                  
                                <img src="{{ asset('/assets/img/avatars/avatar' .  $quiz->avatar .'.png') }}" style="width:25px;">&nbsp;{{ $quiz->nickname }}&nbsp;·&nbsp;
                                조회 : {{ $quiz->viewCount }}&nbsp;·&nbsp;
                                {{ $quiz->updated_at->diffForHumans() }} 수정
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>    
</div>

<script>
    function confirm1(testID) {
        // 모달을 동적으로 생성

        var divToDelete = document.getElementById("noLogin");
        if (divToDelete) {
            // 찾은 요소를 삭제합니다.
            divToDelete.remove();
            this.confirm1(testID);
        } else {
            var modal = document.createElement('div');
            modal.classList.add('modal', 'fade');
            modal.id = 'noLogin'
            modal.setAttribute('data-bs-backdrop', 'static');
            modal.tabIndex = -1;
            modal.setAttribute('aria-hidden', 'true');

            var modalDialog = document.createElement('div');
            modalDialog.classList.add('modal-dialog', 'modal-dialog-centered');
            modalDialog.setAttribute('role', 'document');

            var modalContent = document.createElement('div');
            modalContent.classList.add('modal-content');

            var modalHeader = document.createElement('div');
            modalHeader.classList.add('modal-header');

            var closeButton = document.createElement('button');
            closeButton.type = 'button';
            closeButton.classList.add('btn-close');
            closeButton.setAttribute('data-bs-dismiss', 'modal');
            closeButton.setAttribute('aria-label', 'Close');

            var modalBody = document.createElement('div');
            modalBody.classList.add('modal-body');

            var messageDiv = document.createElement('div');

            var messageHeader = document.createElement('h5');
            messageHeader.innerHTML = '<strong>❗로그인 없이 퀴즈를 풀까요?</strong>';

            var messageText = document.createElement('p');
            messageText.innerHTML = '<strong>로그인하면 랜덤 출제 기능을 사용할 수 있어요.🎲</strong>';

            var modalFooter = document.createElement('div');
            modalFooter.classList.add('modal-footer');

            var loginButton = document.createElement('a');
            loginButton.classList.add('btn', 'btn-primary');
            loginButton.href = '{{ url('login') }}';
            loginButton.innerHTML = '로그인';

            var justPlayButton = document.createElement('a');
            justPlayButton.classList.add('btn', 'btn-primary');
            justPlayButton.href = "{{ url('quiz/solve') }}/" + testID + "/type1";
            justPlayButton.innerHTML = '그냥 풀기';

            // 모달을 조립
            messageDiv.appendChild(messageHeader);
            messageDiv.appendChild(messageText);
            modalBody.appendChild(messageDiv);
            modalFooter.appendChild(loginButton);
            modalFooter.appendChild(justPlayButton);
            modalHeader.appendChild(closeButton);
            modalContent.appendChild(modalHeader);
            modalContent.appendChild(modalBody);
            modalContent.appendChild(modalFooter);
            modalDialog.appendChild(modalContent);
            modal.appendChild(modalDialog);

            // 모달을 원하는 위치에 추가
            var container = document.getElementById('noLoginModal'); // 모달을 추가할 컨테이너 선택
            container.appendChild(modal);
        }

        $(document).ready(function() {
            $('#noLogin').modal('show');
        });
    } 

    function confirmDelete(quizId) {
        if (confirm("퀴즈를 삭제합니다.")) {
            window.location.href = "{{ url('quiz/destroy') }}" + "/" + quizId;
            alert('삭제했습니다.')
        }
        return false;
    }  
</script>

@endsection()