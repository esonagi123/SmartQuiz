@extends('base')

@section('content')
<div id="noLoginModal"></div>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">🔍 검색 결과</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr></tr>
                </thead>
                <tbody>
                    @if (!$quizs->count())
                    <tr>
                        <td>
                            검색 결과가 없습니다.
                        </td>
                    </tr>
                    @else
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
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <nav aria-label="PageNavigation">
        <ul class="mt-4 pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="{{ $quizs->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @for ($i = 1; $i <= $quizs->lastPage(); $i++)
                <li class="page-item {{ $i == $quizs->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $quizs->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item">
                <a class="page-link" href="{{ $quizs->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
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
</script>

@endsection()