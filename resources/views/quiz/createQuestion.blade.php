@extends('base')

@section('content')

<style>
.fade-element {
  opacity: 0; /* 초기에는 투명도 0으로 설정 */
  transition: opacity 1s; /* 투명도 속성에 1초 동안의 트랜지션 적용 */
}

</style>



<div class="fade-element container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">문제 만들기 📝</h4>
    <div class="col-md-12">
        <div class="card mb-4">
            <form method="post" action="{{ route('quiz.store') }}">
                @csrf
                <input type="hidden" class="card-header form-control" name="number" value="0">
                <div class="mt-4 card-body">
                    <div class="mt-2 mb-3">
                            <label for="largeInput" class="form-label">문제를 여기에 적으세요 ✏️</label>
                            <textarea id="largeInput" class="form-control form-control-lg" name="name" placeholder="" rows="5"></textarea>
                    </div>
                    <div class="mt-2 mb-3">
                        <label for="largeSelect" class="form-label">어떤 형태의 문제인가요?</label>
                        <select id="largeSelect" class="form-select form-select-lg" onchange="showHideDiv({{ $testID }})">
                          <option>선택하세요.</option>
                          <option value="1">선택형</option>
                          <option value="2">서술형</option>
                          <option value="3">O/X</option>
                        </select>
                    </div>

                    <div id="hiddenDiv" style="display: none;">
                        <button type="button" id="addButton" class="mb-4 btn rounded-pill btn-primary" onclick="addInput()">보기 추가</button>
                        <div id="inputContainer"></div>
                    </div>
                    
                    <div class="text-end mt-5 mb-3">
                        <button type="button" class="btn rounded-pill btn-primary">문제 추가</button>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn rounded-pill btn-primary">저장하고 끝내기</button>
                    </div>                    
                </div>
            </form>
        </div>      
    </div>
</div>

<script>
    var testID = @json($testID); // Laravel PHP 변수를 JavaScript 변수로 변환
    var questionID = 1; // 테스트를 위한 임시 전역 변수
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    window.addEventListener('load', function() {
        
        // 페이지가 로딩될 때 JavaScript를 사용하여 페이드 효과를 적용
        const fadeElement = document.querySelector('.fade-element');
        fadeElement.style.opacity = 1;
        // 페이지 로딩 후에 투명도를 1로 설정하여 나타나게 함
    
        // 페이지 로딩 시 Question 자동 Store AJAX
        $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        // url: "{{ url('quiz/storeQuestion') }}", // AjaxController -> index 함수 실행
        type: "POST",
        data: { testID: testID }, // ex) $request->input('id') == var movieID
        dataType: "json",
        success: function(data) // data == $response
        {
            questionID = data.questionID;
            alert('AJAX 성공');
        },
        error: function() {
            alert('실패');
        }
        });

    });

    function showHideDiv(testID) {
        var selectBox = document.getElementById("largeSelect");
        var hiddenDiv = document.getElementById("hiddenDiv");
        var testID = testID;
        
        // 선택된 옵션의 값을 가져옵니다.
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    
        // 값이 1(객관식)일 경우
        if (selectedValue === "1") {
            hiddenDiv.style.display = "block";
        } else {
            hiddenDiv.style.display = "none";
        }
    }    

    var inputCount = 0;
    var maxInputs = 5; // 최대 인풋 개수 

    function addInput() {
    
        // 최대 인풋 개수에 도달하면 더 이상 인풋을 추가하지 않음.
        if (inputCount >= maxInputs) {
            alert("최대 " + maxInputs + "개만 만들 수 있어요.");
            return;
        }

        // text 타입의 인풋 태그 생성
        var newTextInput = document.createElement("input");
        newTextInput.type = "text";
        newTextInput.name = "text_option_" + inputCount; // 각 인풋 태그마다 고유한 이름을 설정.
        newTextInput.placeholder = "보기 " + (inputCount + 1) + "번";
        newTextInput.classList.add("form-control");

        // hidden 타입의 인풋 태그 생성
        var newHiddenInput = document.createElement("input");
        newHiddenInput.type = "hidden";
        newHiddenInput.name = "hidden_option_" + inputCount; // 각 인풋 태그마다 고유한 이름을 설정.
        newHiddenInput.value = inputCount + 1; // 값을 설정

        // 삭제 버튼을 생성
        var deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.classList.add("btn", "btn-danger");
        deleteButton.textContent = "삭제";
        deleteButton.onclick = function() {
            removeInput(newTextInput, newHiddenInput, newHiddenInput.value);
        };

        // 인풋 태그와 삭제 버튼을 감싸는 div를 생성
        var inputDiv = document.createElement("div");
        inputDiv.appendChild(newTextInput);
        inputDiv.appendChild(newHiddenInput);
        inputDiv.appendChild(deleteButton);

        // 생성한 div를 inputContainer에 추가
        var inputContainer = document.getElementById("inputContainer");
        inputContainer.appendChild(inputDiv);

        // 인풋 태그마다 고유한 이름을 가지기 위해 inputCount를 증가
        inputCount++;

        $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: "{{ url('quiz/storeChoice') }}", // AjaxController -> index 함수 실행
        type: "POST",
        data: { questionID: questionID, number: newHiddenInput.value }, // ex) $request->input('id') == var movieID
        dataType: "json",
        success: function(data) // data == $response
        {
            alert('choice store success!');
        },
        error: function() {
            alert('fail..');
        }
        });
    }

    // 보기 삭제
    function removeInput(textInput, hiddenInput, hiddenInputValue) {
        alert("보기" + hiddenInputValue + "번을 삭제합니다..");
        var inputContainer = document.getElementById("inputContainer");
        var parentDiv = textInput.parentElement; // 부모 div 요소 가져오기
        inputContainer.removeChild(parentDiv); // 부모 div 요소 제거

        // 인풋 태그 개수 감소
        window.inputCount--; // window: 브라우저 환경에서의 전역 객체
        alert(window.inputCount);

        // 각 인풋 태그의 placeholder 업데이트
        var inputElements = inputContainer.querySelectorAll("input[type='text']");
        for (var i = 0; i < inputElements.length; i++) {
            inputElements[i].placeholder = "옵션 " + (i + 1);
        }

        // hidden 타입의 인풋 태그도 제거
        var hiddenInputParent = hiddenInput.parentElement;
        inputContainer.removeChild(hiddenInputParent);

        // hidden 타입의 인풋 태그의 value를 업데이트
        var hiddenInputElements = inputContainer.querySelectorAll("input[type='hidden']");
        for (var j = 0; j < hiddenInputElements.length; j++) {
            hiddenInputElements[j].value = j; // 원하는 값으로 업데이트
            
        }

        // 삭제 시 동작할 ajax 추가 예정..
        // $.ajax({
        // headers: {'X-CSRF-TOKEN': csrfToken},
        // url: "{{ url('quiz/storeChoice') }}", // AjaxController -> index 함수 실행
        // type: "POST",
        // data: { questionID: questionID, number: newHiddenInput.value }, // ex) $request->input('id') == var movieID
        // dataType: "json",
        // success: function(data) // data == $response
        // {
        //     alert('choice store success!');
        // },
        // error: function() {
        //     alert('fail..');
        // }
        // });

    }

</script>

@endsection()