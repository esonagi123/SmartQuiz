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

        {{-- <form method="patch" action="">
            @csrf
            <div class="card mb-4">
                <input type="hidden" class="card-header form-control" name="number" value="0">
                <div class="mt-4 card-body">
                    <div class="mt-2 mb-3">
                            <label for="largeInput" class="form-label">문제를 여기에 적으세요 ✏️</label>
                            <textarea id="largeInput" class="form-control form-control-lg" name="name" placeholder="" rows="5"></textarea>
                    </div>
                    <div class="mt-2 mb-3">
                        <label for="largeSelect" class="form-label">어떤 형태의 문제인가요?</label>
                        <select id="largeSelect" class="form-select form-select-lg" onchange="showHideDiv()">
                          <option>선택하세요.</option>
                          <option value="1">선택형</option>
                          <option value="2">서술형</option>
                          <option value="3">O/X</option>
                        </select>
                    </div>

                    <div id="hiddenDiv" style="display: none;">
                        <button type="button" id="addButton" class="mb-4 btn rounded-pill btn-primary" onclick="addInput()">보기 추가</button>
                    </div>
                    <div id="inputContainer"></div>

                    <div class="text-end mt-5 mb-3">
                        <button type="button" class="btn rounded-pill btn-primary" onclick="addCard()">문제 추가</button>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn rounded-pill btn-primary">저장하고 끝내기</button>
                    </div>                    
                </div>
            </div>
        </form> --}}

        <div id="cardContainer"></div>
        
    </div>
</div>

<script>
    var testID = @json($testID); // Laravel PHP 변수를 JavaScript 변수로 변환
    // var questionID = 1; // 테스트를 위한 임시 전역 변수
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var cardCount = 0;

    window.addEventListener('load', function() {
        
        // 페이지가 로딩될 때 JavaScript를 사용하여 페이드 효과를 적용
        const fadeElement = document.querySelector('.fade-element');
        // 페이지 로딩 후에 투명도를 1로 설정하여 나타나게 함
        fadeElement.style.opacity = 1;
        
        cardCount++;
        // 페이지 로딩 시 Question 자동 Store AJAX
        $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: "{{ url('quiz/storeQuestion') }}",
        type: "POST",
        data: { testID: testID, number: cardCount },
        dataType: "json",
        success: function(data) // data == $response
        {
            var questionID = data.questionID;
            addCard();
            alert('AJAX 성공' + questionID);
        },
        error: function(data) {
            alert(data.message);
        }
        });

    });

    // function showHideDiv() {
    //     var selectBox = document.getElementById("largeSelect");
    //     var hiddenDiv = document.getElementById("hiddenDiv");
        
    //     // 선택된 옵션의 값을 가져옵니다.
    //     var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    
    //     // 값이 1(객관식)일 경우
    //     if (selectedValue === "1") {
    //         hiddenDiv.style.display = "block";
    //     } else {
    //         hiddenDiv.style.display = "none";
    //     }
    // }

    function showHideDiv(cardCount) {
        var selectBox = document.getElementById("largeSelect"+cardCount);
        var hiddenDiv = document.getElementById("hiddenDiv"+cardCount);
        
        // 선택된 옵션의 값을 가져옵니다.
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    
        // 값이 1(객관식)일 경우
        if (selectedValue === "1") {
            hiddenDiv.style.display = "block";
        } else {
            hiddenDiv.style.display = "none";
        }
    }    

    // $(document).on("change", "#largeSelect", function() {
    //     showHideDiv();
    // });
    
    var inputCount = 0;
    var maxInputs = 5; // 최대 인풋 개수 
    var usedValues = []; // 현재 사용 중인 Value 값을 추적하기 위한 배열

    function addInput(cardCount) {
        // 최대 인풋 개수에 도달하면 더 이상 인풋을 추가하지 않음.
        if (usedValues.length >= maxInputs) {
            alert("최대 " + maxInputs + "개만 만들 수 있어요.");
            return;
        }


        // 사용 가능한 Value 값을 찾아서 할당
        var newValue = findUnusedValue();

        // text 타입의 인풋 태그 생성
        var newTextInput = document.createElement("input");
        newTextInput.type = "text";
        newTextInput.name = "text_option_" + newValue; // 각 인풋 태그마다 고유한 이름을 설정.
        newTextInput.placeholder = "보기 " + (newValue) + "번";
        newTextInput.classList.add("form-control");

        // hidden 타입의 인풋 태그 생성
        var newHiddenInput = document.createElement("input");
        newHiddenInput.type = "hidden";
        newHiddenInput.name = "hidden_option_" + newValue; // 각 인풋 태그마다 고유한 이름을 설정.
        newHiddenInput.value = newValue; // 값을 설정

        // 삭제 버튼을 생성
        var deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.classList.add("btn", "btn-danger");
        deleteButton.textContent = "삭제";
        deleteButton.onclick = function() {
            removeInput(newTextInput, newHiddenInput, newValue);
        };

        // 인풋 태그와 삭제 버튼을 감싸는 div를 생성
        var inputDiv = document.createElement("div");
        inputDiv.appendChild(newTextInput);
        inputDiv.appendChild(newHiddenInput);
        inputDiv.appendChild(deleteButton);

        // 생성한 div를 inputContainer에 추가
        var inputContainer = document.getElementById("inputContainer" + cardCount);
        inputContainer.appendChild(inputDiv);

        // inputCount를 증가하지 않습니다. 대신, 사용한 Value 값을 usedValues 배열에 추가
        usedValues.push(newValue);

        // Ajax로 선택지 정보를 저장할 수 있도록 코드 추가
        saveChoiceToServer(newValue);
    }

    // 보기 삭제
    function removeInput(textInput, hiddenInput, hiddenInputValue) {
        var confirmation = confirm("보기 " + hiddenInputValue + "번을 삭제합니다..");
        
        if (confirmation) {
            // 삭제 시 동작할 ajax
            $.ajax({
                headers: {'X-CSRF-TOKEN': csrfToken},
                url: "{{ url('quiz/destroyChoice') }}",
                type: "DELETE",
                data: { choiceID: hiddenInputValue },
                dataType: "json",
                success: function(data) {
                    alert('Delete Complete!');
                    var inputContainer = document.getElementById("inputContainer");
                    var parentDiv = textInput.parentElement; // 부모 div 요소 가져오기
                    inputContainer.removeChild(parentDiv); // 부모 div 요소 제거

                    // 사용한 Value 값을 usedValues 배열에서 제거
                    usedValues.splice(usedValues.indexOf(hiddenInputValue), 1);

                    // 각 인풋 태그의 placeholder 업데이트
                    var inputElements = inputContainer.querySelectorAll("input[type='text']");
                    for (var i = 0; i < inputElements.length; i++) {
                        var newValue = usedValues[i];
                        inputElements[i].name = "text_option_" + newValue;
                        inputElements[i].placeholder = "보기 " + (newValue) + "번";
                    }
                },
                error: function() {
                    alert('fail..');
                }
            });
        }
    }

    // 사용 가능한 가장 작은 Value 값을 찾아서 반환
    function findUnusedValue() {
        for (var value = 1; value <= maxInputs; value++) {
            if (!usedValues.includes(value)) {
                return value;
            }
        }
        return null; // 모든 값이 사용 중인 경우
    }

    // 선택지 정보를 서버에 저장하는 함수 (Ajax로 호출)
    function saveChoiceToServer(choiceValue) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': csrfToken},
            url: "{{ url('quiz/storeChoice') }}", // AjaxController -> index 함수 실행
            type: "POST",
            data: { questionID: questionID, number: choiceValue }, // ex) $request->input('id') == var movieID
            dataType: "json",
            success: function(data) {
                alert('Choice Store Complete!');
            },
            error: function() {
                alert('fail..');
            }
        });
    }

    function updateQuestion()
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': csrfToken},
            url: "{{ url('quiz/updateQuestuion') }}", // AjaxController -> index 함수 실행
            type: "POST",
            data: { questionID: questionID, number: choiceValue }, // ex) $request->input('id') == var movieID
            dataType: "json",
            success: function(data) {
                alert('Questuion Update Complete!');
            },
            error: function() {
                alert('fail..');
            }
        });

        addCard();
    }

    function addCard2() {
    cardCount++;
    var formData = $("#question").serialize();
    // formData.append('testID', testID);
    // formData.append('number', cardCount);
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: "{{ url('quiz/storeQuestion') }}",
        type: "POST",
        data: { testID: testID, number: cardCount },
        dataType: "json",
        success: function(data) // data == $response
        {
            var questionID = data.questionID;
            addCard();
            alert('AJAX 성공' + questionID);
        },
        error: function(data) {
            alert(data.message);
        }
        });
    }
    

    function addCard() {

        var inputCount = 0;

        var usedValues = [];

        cardCount++;

    var cardHtml = `
        <form id="question">
        <div class="card mb-4 ">
                <input type="hidden" class="card-header form-control" name="number" value="0">
                <div class="mt-4 card-body">
                    <div class="mt-2 mb-3">
                            <label for="largeInput" class="form-label">문제를 여기에 적으세요 ✏️</label>
                            <textarea id="largeInput${cardCount}" class="form-control form-control-lg" name="name" placeholder="" rows="5"></textarea>
                    </div>
                    <div class="mt-2 mb-3">
                        <label for="largeSelect" class="form-label">어떤 형태의 문제인가요?</label>
                        <select id="largeSelect${cardCount}" class="form-select form-select-lg" onchange="showHideDiv(${cardCount})">
                          <option>선택하세요.</option>
                          <option value="1">선택형</option>
                          <option value="2">서술형</option>
                          <option value="3">O/X</option>
                        </select>
                    </div>

                    <div id="hiddenDiv${cardCount}" style="display: none;">
                        <button type="button" id="addButton" class="mb-4 btn rounded-pill btn-primary" onclick="addInput(${cardCount})">보기 추가</button>
                        <div id="inputContainer${cardCount}"></div>
                    </div>
                    
                    <div class="text-end mt-5 mb-3">
                        <button class="btn rounded-pill btn-danger" onclick="removeCard(this)">카드 삭제</button>
                        <button type="button" id="newQuestion" class="btn rounded-pill btn-primary" onclick="addCard2()">문제 추가</button>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn rounded-pill btn-primary">저장하고 끝내기</button>
                    </div>                    
                </div>
        </div>
        </form>
        `;

        // 새로운 카드를 cardContainer에 추가
        var cardContainer = document.getElementById("cardContainer");
        var newCard = document.createElement("div");
        newCard.innerHTML = cardHtml;
        cardContainer.appendChild(newCard);

        var selectElement = newCard.querySelector(`#largeSelect${cardCount}`);
        selectElement.addEventListener("change", function() {
            showHideDiv(cardCount)
        });        
    }
</script>

@endsection()