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
                                @if ($myQuiz->incomplete == "Y")                
                                    <a href="javascript:void(0);" onclick="alert('미완성인 문제입니다.\n수정을 눌러 부족한 부분을 채워주세요.')"><span style="font-size: 17px;">{{ $myQuiz->name }}</span></a><br>
                                @else
                                    <a href="{{ url('quiz/solve/' . $myQuiz->id . "/type1") }}"><span style="font-size: 17px;">{{ $myQuiz->name }}</span></a><br>
                                @endif
                                조회: {{ $myQuiz->viewCount }}&nbsp;&nbsp;|&nbsp;&nbsp;
                                체점 수: {{ $myQuiz->solveCount }}&nbsp;&nbsp;|&nbsp;&nbsp;
                                {{ $myQuiz->updated_at->diffForHumans() }} 업데이트
                                <br>
                                <div class="text-end">
                                    <a class="mt-2 mb-2 btn btn-sm btn-primary" href="{{ url('quiz/' . $myQuiz->id . '/edit') }}"><i class="fa-solid fa-pen"></i>&nbsp; 수정</a>
                                    <a class="mt-2 mb-2 btn btn-sm btn-danger" href="javascript:void(0);" onclick="return confirmDelete({{ $myQuiz->id }}, {{ $myQuizs->currentPage() }});"><i class="fa-solid fa-trash"></i>&nbsp; 삭제</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <nav aria-label="PageNavigation">
        <ul class="mt-4 pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="{{ $myQuizs->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @for ($i = 1; $i <= $myQuizs->lastPage(); $i++)
                <li class="page-item {{ $i == $myQuizs->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $myQuizs->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item">
                <a class="page-link" href="{{ $myQuizs->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
    function confirmDelete(quizId, currentPage) {
        if (confirm("퀴즈를 삭제합니다.")) {
            window.location.href = "{{ url('quiz/destroy') }}" + "/" + quizId + "/" + currentPage;
        }
        return false;
    }
</script>
@endsection()