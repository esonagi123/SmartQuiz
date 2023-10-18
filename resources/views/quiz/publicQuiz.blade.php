@extends('base')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card">
        <h5 class="card-header">🌏 공개 퀴즈 - 누구나 풀 수 있어요</h5>
        <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
            <tr></tr>
            </thead>
            <tbody>
            @foreach ($quizs as $quiz)
                <tr>
                    <td>
                        <span style="font-size: 20px;">&nbsp;{{ $quiz->name }}</span><br>
                        <i class="fa-solid fa-user-large"></i>&nbsp;{{ $quiz->uid }}&nbsp;&nbsp;
                        <i class="fa-solid fa-eye"></i>&nbsp;조회 수 : ?? &nbsp;&nbsp;
                        <i class="fa-solid fa-calendar-days"></i>&nbsp;{{ $quiz->created_at->diffForHumans() }}
                        
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection()