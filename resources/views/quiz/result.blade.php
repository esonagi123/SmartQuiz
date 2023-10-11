@extends('base')

@section('content')


당신의 점수는 {{ $score }} 점

@foreach ($returnInputs as $returnInput)
    당신이 선택한 답은 {{ implode(', ', $returnInput['input']) }}
@endforeach

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
    

</style>

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

@endsection()