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
</div>
@endsection()