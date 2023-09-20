@extends('base')

@section('content')

<style>
/* CSS 스타일 */
.fade-element {
  opacity: 0; /* 초기에는 투명도 0으로 설정 */
  transition: opacity 1s; /* 투명도 속성에 0.5초 동안의 트랜지션 적용 */
}

</style>

<script>
        // 페이지가 로딩될 때 JavaScript를 사용하여 페이드 효과를 적용
        window.addEventListener('load', function() {
            const fadeElement = document.querySelector('.fade-element');
            fadeElement.style.opacity = 1; // 페이지 로딩 후에 투명도를 1로 설정하여 나타나게 함
        });
</script>

<div class="fade-element container-xxl flex-grow-1 container-p-y">
    <div class="col-md-12">
        <div class="card mb-4">
            <form method="post" action="{{ route('quiz.store') }}">
                @csrf
                <h5 class="card-header">ㅁㄴㅇㄹ</h5>
                <div class="card-body">
                    <div class="mt-2 mb-3">
                            <label for="largeInput" class="form-label">이 시험의 이름을 알려주세요.</label>
                            <input id="largeInput" class="form-control form-control-lg" type="text" name="name" placeholder="">
                    </div>
                    <div class="mt-2 mb-3">
                            <label for="largeInput" class="form-label">이 시험의 주제는 무엇인가요?</label>
                            <input id="largeInput" class="form-control form-control-lg" type="text" name="subject" placeholder="시험, 상식, 퀴즈 등">
                            <div id="floatingInputHelp" class="form-text"></div>
                    </div>                  
                    <div class="text-end">
                        <button type="submit" class="btn rounded-pill btn-primary">NEXT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection()