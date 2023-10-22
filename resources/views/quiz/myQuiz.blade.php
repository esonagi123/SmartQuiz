@extends('base')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <div class="d-flex justify-content-between">
        <h5 class="card-header">✍️ 나의 퀴즈</h5>
        <a class="card-header" href="{{ url('quiz/create') }}">퀴즈 만들기</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr></tr>
                </thead>
                <tbody>
                    @foreach ($myQuizs as $myQuiz)
                        <tr>
                            <td>
                                @if ($myQuiz->secret == "N")
                                    <p style="margin-bottom: 8px;" class="badge bg-label-primary">공개</p>
                                    @if ($myQuiz->incomplete == "Y")
                                        <span class="badge bg-label-danger">미완성</span>
                                    @endif
                                @elseif ($myQuiz->secret == "Y")
                                    <p style="margin-bottom: 8px;" class="badge bg-label-secondary">비공개</p>
                                    @if ($myQuiz->incomplete == "Y")
                                        <span class="badge bg-label-danger">미완성</span>
                                    @endif                                    
                                @endif
                                <br>                         
                                <a href="{{ url('quiz/solve/' . $myQuiz->id . "/type1") }}"><span style="font-size: 20px;">{{ $myQuiz->name }}</span></a><br>
                                조회: ?? &nbsp;&nbsp;|&nbsp;&nbsp;
                                {{ $myQuiz->updated_at->diffForHumans() }} 업데이트
                                <br>
                                <div class="text-end">
                                    <a class="mt-2 mb-2 btn btn-sm btn-primary" href="{{ url('quiz/' . $myQuiz->id . '/edit') }}"><i class="fa-solid fa-pen"></i>&nbsp; 수정</a>
                                    <a class="mt-2 mb-2 btn btn-sm btn-danger" href="{{ url('quiz/' . $myQuiz->id . '/edit') }}"><i class="fa-solid fa-trash"></i>&nbsp; 삭제</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection()