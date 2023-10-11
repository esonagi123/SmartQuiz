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

<div class="button-bar">
    <button type="button" class="btn btn-danger fixed-btn" onclick="">나가기</button>
    <button type="button" class="btn btn-warning fixed-btn" onclick="submitForm()">제출</button>
</div>

<div class="fade-element container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-1">{{ $testModel->name }}</h4>
    <h6 class="pb-1 text-muted">퀴즈 풀기</h6>
    <div id="questionContainer" class="col-md-12">
        <form id="test_{{ $testModel->id }}" method="post" action="{{ url('quiz/result/' . $testModel->id) }}">
        @csrf
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
                                                @else
                                                &nbsp;&nbsp; {{ $choice->content }}
                                                @endif
                                            </label>
                                    @endforeach
                                    </div>
                                </div>
                            @else
                                <p>객관식이 아님</p>
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

</script>

@endsection()