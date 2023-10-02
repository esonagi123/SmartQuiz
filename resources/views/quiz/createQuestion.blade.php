@extends('base')

@section('content')

<style>
    .fade-element {
    opacity: 0; /* 초기에는 투명도 0으로 설정 */
    transition: opacity 1s; /* 투명도 속성에 1초 동안의 트랜지션 적용 */
    }

    body {
    /* Add some padding to the bottom to prevent the fixed bar from overlapping content */
    margin-bottom: 60px; /* Adjust the value based on the height of your button bar */
    }

    .button-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #333;
    padding: 10px;
    text-align: center;
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

<!-- Fixed Button Bar -->
<div class="button-bar text-center">
    <button type="button" id="newQuestion" class="btn rounded-pill btn-icon btn-success" onclick="addCard2()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>문제 추가</span>"><box-icon name='plus' flip='horizontal' color='#ffffff' ></box-icon></button>
    <button type="button" class="btn rounded-pill btn-icon btn-warning" onclick="save()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>저장</span>"><box-icon name='save' type='solid' animation='tada' flip='horizontal' color='#ffffff' ></box-icon></button>
    <button type="button" class="btn rounded-pill btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>초기화</span>"><box-icon name='reset' flip='horizontal' color='#ffffff' ></box-icon></button>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        {{-- <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle"></h5>
        </div> --}}
        <div class="modal-body mt-3">
            <div class="mb-4">
                <h5><strong>❗만들고 있던 문제가 있어요 🧐</strong></h5>
                <p><strong>이어서 만들까요?</strong></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" onclick="reset()">초기화</button>
            <a class="btn btn-primary" href="{{ url('quiz/' . $testID . '/edit' ) }}">이어서 만들기</a>
        </div>
        </div>
    </div>
</div>


<script>

    var testID = @json($testID); // Laravel PHP 변수를 JavaScript 변수로 변환
    // var questionID = 1; // 테스트를 위한 임시 전역 변수
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    var cardCount = 0; // 문제 수
    var maxInputs = 5; // 최대 보기 개수 
    var inputCount = 0; // 보기 추가 횟수
    var usedValues = {}; // 초기화    

    window.addEventListener('load', function() {
        // 페이지 로딩 시 자동 실행
        
        const fadeElement = document.querySelector('.fade-element'); // JavaScript를 사용하여 페이드 효과를 적용
        fadeElement.style.opacity = 1; // 투명도를 1로 설정하여 나타나게 함
        
        cardCount++;

        // Question 생성
        $.ajax({
            headers: {'X-CSRF-TOKEN': csrfToken},
            url: "{{ url('quiz/storeQuestion') }}",
            type: "POST",
            data: { testID: testID, number: cardCount },
            dataType: "json",
            success: function(data) {
                if (data.success === true) {
                    var questionID = data.questionID;
                    addCard(questionID);
                    alert('문제 생성 완료 QID : ' + questionID);
                } else {
                    $('#modalCenter').modal('show');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("AJAX 오류: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // 문제 타입 선택
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

    // 선택지 만들기
    function addInput(cardCount, questionID) {
        if (!usedValues[cardCount]) {
            usedValues[cardCount] = [];
        }
        console.log(usedValues[cardCount]);
        
        // 최대 인풋 개수에 도달하면 더 이상 인풋을 추가하지 않음.
        if (usedValues[cardCount].length >= maxInputs) {
            alert("최대 " + maxInputs + "개만 만들 수 있어요.");
            return;
        }

        // 사용 가능한 Value 값을 찾아서 할당
        var newValue = findUnusedValue(cardCount);

        // 사용한 Value 값을 usedValues 배열에 추가
        usedValues[cardCount].push(newValue);
        
        // Ajax로 선택지 정보를 저장할 수 있도록 코드 추가
        saveChoiceToServer(cardCount, newValue, questionID);
        
    }

    // 선택지 정보를 서버에 저장 후 input 생성
    function saveChoiceToServer(cardCount, choiceValue, questionID) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': csrfToken},
            url: "{{ url('quiz/storeChoice') }}", // AjaxController -> index 함수 실행
            type: "POST",
            data: { questionID: questionID, number: choiceValue }, // ex) $request->input('id') == var movieID
            dataType: "json",
            success: function(data) {
                alert('Choice Store Complete! : ' + data.choiceID);

                // 내용 text input
                var newTextInput = document.createElement("input");
                newTextInput.type = "text";
                newTextInput.name = "choice" + choiceValue; // 각 태그마다 고유한 이름을 설정.
                newTextInput.placeholder = "보기 " + (choiceValue) + "번";
                newTextInput.classList.add("form-control");

                // 번호 hidden input
                var newHiddenInput = document.createElement("input");
                newHiddenInput.type = "hidden";
                newHiddenInput.name = "choiceNumber" + choiceValue; // 각 태그마다 고유한 이름을 설정.
                newHiddenInput.value = choiceValue; // 사용 가능한 Value 값

                // 삭제 버튼을 생성
                var deleteButton = document.createElement("button");
                deleteButton.type = "button";
                deleteButton.classList.add("btn", "btn-icon", "btn-danger");
                deleteButton.innerHTML = "<i class='bx bxs-trash-alt' ></i>";
                deleteButton.onclick = function() {
                    removeInput(newTextInput, newHiddenInput, choiceValue, questionID, cardCount);
                };

                // 인풋 태그와 삭제 버튼을 감싸는 div를 생성
                var inputDiv = document.createElement("div");
                inputDiv.appendChild(newTextInput);
                inputDiv.appendChild(newHiddenInput);
                inputDiv.appendChild(deleteButton);

                // 생성한 div를 inputContainer에 추가
                var inputContainer = document.getElementById("inputContainer" + cardCount);
                inputContainer.appendChild(inputDiv);                
            },
            error: function() {
                alert('fail..');
            }
        });
    }

    // 선택지 삭제
    function removeInput(textInput, hiddenInput, hiddenInputValue, questionID, cardCount) {
        var confirmation = confirm(questionID + "(" + cardCount + ") 의 보기" + hiddenInputValue + "번을 삭제합니다..");
        
        if (confirmation) {
            // 삭제 시 동작할 ajax
            $.ajax({
                headers: {'X-CSRF-TOKEN': csrfToken},
                url: "{{ url('quiz/destroyChoice') }}",
                type: "DELETE",
                data: { choiceID: hiddenInputValue, questionID: questionID },
                dataType: "json",
                success: function(data) {
                    alert('Delete Complete!');
                    var inputContainer = document.getElementById("inputContainer" + cardCount);
                    var parentDiv = textInput.parentElement; // 부모 div 요소 가져오기
                    inputContainer.removeChild(parentDiv); // 부모 div 요소 제거

                    console.log('usedValues[cardCount]:', usedValues[cardCount]);
                    console.log('hiddenInputValue:', hiddenInputValue);
                    console.log('Index of hiddenInputValue:', index);

                    var index = usedValues[cardCount].indexOf(hiddenInputValue);
                    if (index !== -1) {
                        usedValues[cardCount].splice(index, 1);
                    }                    

                    alert('콘솔');

                    console.log('usedValues[cardCount]:', usedValues[cardCount]);
                    console.log('hiddenInputValue:', hiddenInputValue);
                    console.log('Index of hiddenInputValue:', index);
                    
                    // 각 인풋 태그의 placeholder 업데이트
                    var inputElements = inputContainer.querySelectorAll("input[type='text']");
                    for (var i = 0; i < inputElements.length; i++) {
                        var newValue = usedValues[cardCount][i];
                        inputElements[i].name = "choice" + newValue;
                        inputElements[i].placeholder = "보기 " + (newValue) + "번";
                    }
                },
                error: function() {
                    alert('fail..');
                }
            });
        }
    }

    // 보기의 사용 가능한 가장 작은 Value 값을 찾아서 반환
    function findUnusedValue(cardCount) {
        for (var value = 1; value <= maxInputs; value++) {
            if (!usedValues[cardCount].includes(value)) {
                return value;
            }
        }
        return null; // 모든 값이 사용 중인 경우
    }

    // 문제 추가 시 Question + Choice 업데이트 
    function updateQuestion() {
        var formData = $("#question" + (cardCount-1)).serialize();
        // var form = document.getElementById("question" + cardCount);
        // var formData = new FormData(form);
        console.log(formData);
        $.ajax({
            headers: {'X-CSRF-TOKEN': csrfToken},
            url: "{{ url('quiz/updateQuestion') }}", // AjaxController -> index 함수 실행
            type: "PATCH",
            data: formData, // ex) $request->input('id') == var movieID
            dataType: "json",
            success: function(data) {
                alert('Question Update Complete!');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    alert("AJAX 오류: " + textStatus + " - " + errorThrown);
                }
        });
    }

    // 문제 추가 버튼을 누르면
    function addCard2() {
        updateQuestion();

        $.ajax({
            headers: {'X-CSRF-TOKEN': csrfToken},
            url: "{{ url('quiz/storeQuestion') }}",
            type: "POST",
            data: { testID: testID, number: cardCount },
            dataType: "json",
            success: function(data) {
                if (data.success === true) {
                    var questionID = data.questionID;
                    addCard(questionID);
                    alert('문제 생성 완료 QID : ' + questionID);
                } else {
                    alert(data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("AJAX 오류: " + textStatus + " - " + errorThrown);
            }
        });
    }

    // 문제 카드 생성
    function addCard(questionID) {

        inputCount = 0;
        // usedValues = [];
        
        var cardHtml = `
        <form id="question${cardCount}">
            <input type="hidden" name="questionID" value="${questionID}">
            <div class="card mb-4">
                <input type="hidden" class="card-header form-control" name="number" value="${cardCount}">
                <div class="mt-4 card-body">
                    <div class="mt-2 mb-3">
                        <label for="largeInput" class="form-label">문제를 여기에 적으세요 ✏️</label>
                        <textarea id="largeInput${cardCount}" class="form-control form-control-lg" name="name${cardCount}" placeholder="" rows="5"></textarea>
                    </div>
                    <div class="mt-2 mb-3">
                        <label for="largeSelect" class="form-label">어떤 형태의 문제인가요?</label>
                        <select id="largeSelect${cardCount}" class="form-select form-select-lg" name="gubun${cardCount}" onchange="showHideDiv(${cardCount})">
                            <option>선택하세요.</option>
                            <option value="1">선택형</option>
                            <option value="2">서술형</option>
                            <option value="3">O/X</option>
                        </select>
                    </div>
                    <div id="hiddenDiv${cardCount}" style="display: none;">
                        <button type="button" id="addButton" class="mb-4 btn rounded-pill btn-primary" onclick="addInput(${cardCount}, ${questionID})">보기 추가</button>
                        <div id="inputContainer${cardCount}"></div>
                    </div>
                    <div class="text-end mt-5 mb-3">
                        <button class="btn rounded-pill btn-danger" onclick="removeCard(this)">카드 삭제</button>
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
        
        cardCount++;
    }

    // 이 시험의 모든 문제+선택지 삭제
    function reset() {
        var confirmation = confirm("이 시험에서 생성된 모든 문제를 삭제합니다.");
        if (confirmation) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': csrfToken},
                url: "{{ url('quiz/reset') }}",
                type: "DELETE",
                data: { testID: testID },
                dataType: "json",
                success: function(data) {
                    if (data.success === true) {
                        alert('모든 문제를 삭제했어요.')
                        location.reload()
                    } else {
                        alert('초기화 실패!')
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("AJAX 오류: " + textStatus + " - " + errorThrown);
                }
            });
        }
    }

    // 전체 저장
    function save() {
        cardCount--
        alert('현재 cardCount : ' + cardCount);
        
        for (var i = 1; i <= cardCount; i++) {
            alert(i + "번 문제를 저장합니다..");
            var formData = $("#question" + i).serialize();
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': csrfToken},
                url: "{{ url('quiz/updateQuestion') }}",
                type: "PATCH",
                data: formData,
                dataType: "json",
                success: function(data) {
                    alert("완료!");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert("AJAX 오류: " + textStatus + " - " + errorThrown);
                }
            }); 
        }
        alert('i 초기화..');
        i = 1;
    }
</script>

@endsection()