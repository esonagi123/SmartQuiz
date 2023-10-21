@extends('base')

@section('content')
<style>
    .fade-element {
    opacity: 0; /* 초기에는 투명도 0으로 설정 */
    transition: opacity 1s; /* 투명도 속성에 1초 동안의 트랜지션 적용 */
    }

    body {
    /* Add some padding to the bottom to prevent the fixed bar from overlapping content */
    margin-bottom: 60px; /* Adjust the value based on the height of your button bar */
    }

    .button-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    padding: 10px;
    text-align: right;
    z-index: 100;
    }

    .fixed-btn {
        margin-right: 8px;
    }
</style>


{{-- @if (!Auth::check())
    <script>
        $(document).ready(function() {
            $('#noLogin').modal('show');
        });
    </script>
@elseif (Auth::check())
    <script>
        $(document).ready(function() {
            $('#noLogin').modal('show');
        });
    </script>
@endif --}}

{{-- <!-- Modal (data-bs-backdrop="static" : 안사라지게)-->
<div class="modal fade" id="noLogin" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle"></h5>
            </div>
            <div class="modal-body mt-3">
                <div class="mb-4">
                    <h5><strong>❗로그인 없이 퀴즈를 풀까요?</strong></h5>
                    <p><strong>로그인하면 랜덤 출제 기능을 사용할 수 있어요. 🎲</strong></p>
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="{{ url('login') }}">로그인</a>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">그냥 풀기</button>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="button-bar">
    @if (Auth::check() && $type == '1')
        <button type="button" class="btn btn-primary fixed-btn" onclick="randomQuiz({{ $testModel->id }})">랜덤 출제&nbsp;<i class="fa-solid fa-dice fa-shake"></i></button>
    @elseif (Auth::check() && $type == '2')
        <a class="btn btn-primary fixed-btn" href="{{ url('quiz/solve/' . $testModel->id . "/type1") }}">일반 출제&nbsp;<i class="fa-solid fa-arrow-rotate-left"></i></a>
    @endif
    <button type="button" class="btn btn-warning fixed-btn" onclick="submitForm()">제출!</button>
    <button type="button" class="btn btn-danger fixed-btn" onclick="exit()">나가기</button>
</div>

<div class="fade-element container-xxl flex-grow-1 container-p-y">
    <div class="text-center">
    <h2 class="fw-bold py-1">{{ $testModel->name }}</h2>
    <h5 class="pb-1 text-muted">{{ $testModel->subject }}</h5>
    </div>
    <div id="questionContainer" class="col-md-12">
        <form id="test_{{ $testModel->id }}" method="post" action="{{ url('quiz/result/' . $testModel->id) }}">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
            @foreach($items['questions'] as $question)
                <section id="Q{{ $question->number }}">
                    <div class="card mb-4">
                        <h5 class="card-header"><strong>문제 {{ $question->number }}</strong></h5>
                        <div class="card-body">
                            <!-- 문제 내용 -->
                            {!! $question->question !!}
                            @if ($items['choices'][$question->id] && $question->gubun == "1")
                                <div id="inputContainer{{ $question->number }}">
                                    <div class="list-group">
                                    @foreach ($items['choices'][$question->id] as $choice)
                                            <label class="list-group-item">
                                                <input class="form-check-input me-1" type="checkbox" name="Q{{ $question->number }}answer{{ $choice->number }}" value="{{ $choice->number }}">
                                                @if ($choice->content == "")
                                                    &nbsp;&nbsp; ❗ 선택지 내용이 없어요.
                                                @elseif ($type == '1')
                                                    &nbsp;&nbsp; ({{ $choice->number }})&nbsp;&nbsp;&nbsp;{{ $choice->content }}
                                                @elseif ($type == '2')
                                                    &nbsp;&nbsp;{{ $choice->content }}
                                                @endif
                                            </label>
                                    @endforeach
                                    </div>
                                </div>
                            @elseif ($question->gubun == "2")
                                <div id="shortAnswerDiv{{ $question->number}}" style="display: block;">
                                    <input type="text" class="form-control" name="shortAnswer{{ $question->number }}" placeholder="{{ $question->answer }}" value="">
                                    <br><label class="form-label">- 복수 정답이 있을 경우 콤마(,)로 구분합니다.</label>
                                    <br><label class="form-label">- 하나라도 맞을 경우 정답 처리됩니다.</label>
                                    <br><label class="form-label">- 띄어쓰기는 구분하지 않습니다. </label>
                                </div>
                            @else
                            <p>미완성인 문제입니다.</p>
                            @endif
                        </div>
                    </div>
                </section>
            @endforeach
        </form>
    </div>
</div>

<script>

    window.addEventListener('load', function() {
        // 페이지 로딩 시 자동 실행
        const fadeElement = document.querySelector('.fade-element'); // JavaScript를 사용하여 페이드 효과를 적용
        fadeElement.style.opacity = 1; // 투명도를 1로 설정하여 나타나게 함
    });

    function submitForm() {
        var form = document.getElementById('test_{{ $testModel->id }}');
        form.submit();
    }

    function randomQuiz(testID) {
      // confirm 창을 띄우고 사용자의 선택을 확인합니다.
      var isConfirmed = confirm("문제를 랜덤으로 출제합니다.\n❗ 입력한 내용이 사라집니다.");

      // 사용자가 확인을 눌렀을 때
      if (isConfirmed) {
        var url = "{{ url('quiz/solve') }}/" + testID + "/type2";
        window.location.href = url;
      } else {
        return;
      }
    }

    function exit() {
      // confirm 창을 띄우고 사용자의 선택을 확인합니다.
      var isConfirmed = confirm("메인으로 이동합니다.");

      // 사용자가 확인을 눌렀을 때
      if (isConfirmed) {
        var url = "{{ url('quiz') }}";
        window.location.href = url;
      } else {
        return;
      }
    }
</script>

@endsection()