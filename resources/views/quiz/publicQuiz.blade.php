@extends('base')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">🌏 공개 퀴즈</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr></tr>
                </thead>
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
@endsection()