@extends('base')

@section('content')

<style>
    .result-container {
        position: relative;
        display: flex;
        align-items: center;
    }
    .wrong {
        position: absolute;
        top: 8px;
        right: 20px;   
        width: 60px;
        height: 70px;
    }

    .correct {
        position: absolute;
        top: 0px;
        right: 7px;   
        width: 85px;
        height: 70px;
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

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="modalCenterTitle">나가기</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>  
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <p>이 퀴즈의 점수는..</p>
                    @if ($score == 100)
                        <h2><strong>🎉 {{ number_format($score, 0, '.', '') }}점! 🎉</strong></h2>
                        <h5>틀린 문제가 없어요 👏</h5>
                    @else
                        <h2><strong>{{ number_format($score, 0, '.', '') }}점!</strong></h2>
                        틀린 문제는
                        @foreach ($wrongQuestions as $wrongQuestion)
                        {{ $wrongQuestion['number'] }}번
                        @endforeach
                        이네요.                    
                    @endif
                </div>
                <div class="modal-footer">          
                    <a class="btn btn-warning" href="{{ url('/quiz/solve/'. $testID . '/type1')}}" >다시 풀기</a>
                    <a class="btn btn-danger" href="{{ url('/quiz') }}">나가기</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="button-bar">
    <a class="btn btn-warning fixed-btn" href="{{ url('/quiz/solve/'. $testID . '/type1')}}">다시 풀기</a>
    <a class="btn btn-danger fixed-btn" href="{{ url('/quiz') }}">나가기</a>
</div>

<div class="fade-element container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-1">{{ $testModel->name }}</h4>
    <h6 class="pb-1 text-muted">결과</h6>
    <div id="questionContainer" class="col-md-12">
        <form id="test_{{ $testModel->id }}" method="post" action="">
        @csrf
            @foreach($items['questions'] as $question)
                <section id="Q{{ $question->number }}">
                    <div class="card mb-4">
                        <div class="" style="display: flex;">
                            <h5 class="card-header" style="position: relative;">&nbsp;<strong>문제 {{ $question->number }}</strong></h5>
                            <div class="result-container">
                              @if (collect($wrongQuestions)->contains('number', $question->number))
                                <img class="mt-1 wrong" src="{{ asset('assets/img/result/wrong.png') }}">
                              @else
                              <img class="mt-1 correct" src="{{ asset('assets/img/result/correct.png') }}">
                              @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <!-- 문제 내용 -->
                            {!! $question->question !!}
                            @if ($items['choices'][$question->id] && $question->gubun == "1")
                                <div id="inputContainer{{ $question->number }}">
                                    <div class="list-group">
                                    @foreach ($items['choices'][$question->id] as $choice)
                                        <label class="list-group-item">
                                            @php
                                                $selectedChoices = collect($returnInputs)->filter(function($input) use ($question, $choice) {
                                                    return $input['number'] == $question->number && in_array($choice->number, $input['input']);
                                                });
                                            @endphp
                                    
                                            <input class="form-check-input me-1" 
                                                   type="checkbox" 
                                                   name="Q{{ $question->number }}answer{{ $choice->number }}" 
                                                   value="{{ $choice->number }}"
                                                   @if ($selectedChoices->isNotEmpty()) disabled checked @else disabled @endif
                                            >
                                    
                                            @if ($choice->content == "")
                                                &nbsp;&nbsp; ❗ 선택지 내용이 없어요.
                                            @else
                                                &nbsp;&nbsp; {{ $choice->content }}
                                            @endif
                                        </label>
                                    @endforeach
                                    </div>
                                </div>
                            @elseif ($question->gubun == "2")
                                <div id="shortAnswerDiv{{ $question->number}}" style="display: block;">
                                    <input type="text" class="form-control" name="shortAnswer{{ $question->number }}" value="{{ $returnAnswers[$question->number]['value'] }}" readonly>
                                    <br><label class="form-label">- 복수 정답이 있을 경우 콤마(,)로 구분합니다.</label>
                                    <br><label class="form-label">- 하나라도 맞을 경우 정답 처리됩니다.</label>
                                    <br><label class="form-label">- 띄어쓰기는 구분하지 않습니다. </label>
                                </div>
                            @else
                                <p>문제가 올바르지 않습니다.</p>
                            @endif
                            <div class="mt-3 text-end">
                                <button type="button" class="btn btn-primary">정답 확인</button>
                            </div>
                        </div>
                    </div>
                </section>
            @endforeach
        </form>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        $('#modal').modal('show');
    });
</script>

@endsection()