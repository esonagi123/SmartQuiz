@extends('base')

@section('content')


당신의 점수는 {{ $score }} 점

@foreach ($wrongQuestions as $wrongQuestion)
    틀린 문제는 {{ $wrongQuestion }} 입니다.
@endforeach

@endsection()