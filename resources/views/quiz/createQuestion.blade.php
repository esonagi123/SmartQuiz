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

    /* 선택지 삭제 버튼 */
    .choice-delete-btn {
        margin-left: 6px;
    }

    .fixed-btn {
        margin-right: 8px;
    }

    .insert {
    padding: 20px 30px;
    display: block;
    width: 600px;
    margin: 5vh auto;
    height: 90vh;
    border: 1px solid #dbdbdb;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    }
    .insert .file-list {
    height: 200px;
    overflow: auto;
    border: 1px solid #989898;
    padding: 10px;
    }
    .insert .file-list .filebox p {
    font-size: 14px;
    margin-top: 10px;
    display: inline-block;
    }
    .insert .file-list .filebox .delete i{
    color: #ff5353;
    margin-left: 5px;
    }

</style>

<script src="https://cdn.tiny.cloud/1/tjtgh1g19ijslhffx1hwfpcnu729wk7cmytgbnp8nxepksjn/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>


<div class="fade-element container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">문제 만들기 📝</h4>
    <div id="questionContainer" class="col-md-12">
    
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
    <button type="button" id="newQuestion" class="btn rounded-pill btn-icon btn-success fixed-btn" onclick="addCard2()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>문제 추가</span>"><box-icon name='plus' flip='horizontal' color='#ffffff' ></box-icon></button>
    <button type="button" class="btn rounded-pill btn-icon btn-secondary fixed-btn" onclick="sortAndRender()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>문제 정렬</span>"><box-icon name='sort-up' color='#ffffff' ></box-icon></button>
    <button type="button" class="btn rounded-pill btn-icon btn-warning fixed-btn" onclick="save()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>저장</span>"><box-icon name='save' type='solid' animation='tada' flip='horizontal' color='#ffffff' ></box-icon></button>
    <button type="button" class="btn rounded-pill btn-icon btn-primary fixed-btn" onclick="exit()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>나가기</span>"><box-icon name='exit' color='#ffffff' ></box-icon></button>
    <button type="button" class="btn rounded-pill btn-icon btn-danger fixed-btn" onclick="reset()" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<span>초기화</span>"><box-icon name='reset' flip='horizontal' color='#ffffff' ></box-icon></button>
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
</div>

<!-- Modal -->
<div class="modal fade" id="modal2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <h5 class="modal-title" id="modalCenterTitle">나가기</h5> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>  
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h5><strong>그만 만들까요?</strong></h5>
                    <p><strong>❗저장되지 않은 항목은 사라져요 🤯</strong></p>
            </div>
            <div class="modal-footer">          
                <button type="button" class="btn btn-warning" onclick="save()">저장</button>
                <a class="btn btn-danger" href="#">나가기</a>
            </div>
            </div>
        </div>
    </div>
</div>

<script>
    var shouldShowWarning = true;
    window.addEventListener('beforeunload', function (event) {
            if (shouldShowWarning) {
            // 이벤트의 기본 동작을 취소하여 브라우저의 기본 경고 메시지를 표시하지 않습니다.
            event.preventDefault();

            // 사용자에게 표시할 경고 메시지
            var message = "변경 사항을 저장하시겠습니까?";
            event.returnValue = message; // 표준
            return message; // 일부 브라우저에서도 동작합니다.
        }
    });

    var testID = @json($testID); // Laravel PHP 변수를 JavaScript 변수로 변환
    var csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    var cardCount; // 문제 수
    var cardArray = [];

    var maxInputs = 5; // 최대 보기 개수 
    var usedValues = {}; // 초기화
    
    var fileNo = [];
    var filesArr = {};

    // 페이지 로딩 시 자동 실행
    window.addEventListener('load', function() {
        const fadeElement = document.querySelector('.fade-element'); // JavaScript를 사용하여 페이드 효과를 적용
        fadeElement.style.opacity = 1; // 투명도를 1로 설정하여 나타나게 함
        
        // cardCount++;
        cardCount = findUnusedQuestion();
        cardArray.push(cardCount);

        // 모달이 닫힐 경우
        // $('#modal2').on('hidden.bs.modal', function () {
        //     shouldShowWarning = true;
        // });

        // 모달이 닫힐 경우
        $('#modalCenter').on('hidden.bs.modal', function () {
            $('#modalCenter').modal('show');
        });        

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
                    shouldShowWarning = false;
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("AJAX 오류: " + textStatus + " - " + errorThrown);
            }
        });
    });

    // 문제 타입 선택
    function showHideDiv(cardCount, questionID) {
        var selectBox = document.getElementById("largeSelect"+cardCount);
        var hiddenDiv = document.getElementById("hiddenDiv"+cardCount);
        
        // 선택된 옵션의 값을 가져옵니다.
        var selectedValue = selectBox.options[selectBox.selectedIndex].value;
    
        // 값이 1(객관식)일 경우
        if (selectedValue === "1") {
            hiddenDiv.style.display = "block";
            
            if (!usedValues[cardCount] || usedValues[cardCount].length === 0) {
                for (i = 0; i < maxInputs; i++) {
                    addInput(cardCount, questionID);
                    window.location.href = `#Q${cardCount}`;
                }
            }

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
                // alert('Choice Store Complete! : ' + data.choiceID);

                // 정답 체크 checkBox
                var newCheckBox = document.createElement('input');
                newCheckBox.classList.add("form-check-input", "mt-0");
                newCheckBox.type = "checkbox";
                newCheckBox.name = "answer" + choiceValue;
                newCheckBox.value = choiceValue;

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
                deleteButton.classList.add("flex-end", "btn", "btn-icon", "btn-danger", "choice-delete-btn");
                deleteButton.innerHTML = "<i class='bx bxs-trash-alt' ></i>";
                deleteButton.onclick = function() {
                    removeInput(newInputGroup, newTextInput, newHiddenInput, choiceValue, questionID, cardCount);
                };             


                // input Group text
                var newInputGroupText = document.createElement('div');
                newInputGroupText.classList.add("input-group-text");  
                newInputGroupText.appendChild(newCheckBox);

                // input Group
                var newInputGroup = document.createElement('div');
                newInputGroup.classList.add("input-group");
                newInputGroup.appendChild(newInputGroupText);
                newInputGroup.appendChild(newTextInput);


                // 인풋 태그와 삭제 버튼을 감싸는 div를 생성
                var inputDiv = document.createElement("div");
                var divID = "Q" + cardCount + "_choice" + choiceValue;
                inputDiv.id = divID;
                inputDiv.style.display = "flex";
                inputDiv.classList.add("mb-3")
                // inputDiv.appendChild(newTextInput);
                inputDiv.appendChild(newInputGroup);
                inputDiv.appendChild(newHiddenInput);
                inputDiv.appendChild(deleteButton);

                // 생성한 div를 inputContainer에 추가
                var inputContainer = document.getElementById("inputContainer" + cardCount);
                inputContainer.appendChild(inputDiv);

                sortAndRenderChoices(cardCount)
                   
            },
            error: function() {
                alert('fail..');
            }
        });
    }

    // 선택지 정렬 및 화면에 다시 렌더링
    function sortAndRenderChoices(cardCount) {
        // 선택지 컨테이너
        var inputContainer = document.getElementById("inputContainer" + cardCount);

        // 컨테이너의 자식 DIV들의 ID를 기준으로 오름차순 정렬
        var sortedChoices = Array.from(inputContainer.children).sort((a, b) => {
            var idA = a.id; // ID 추출
            var idB = b.id;
            return idA.localeCompare(idB); // 문자열 비교로 정렬
        });

        // 정렬 후 Input 컨테이너를 갱신
        inputContainer.innerHTML = ''; // 기존 내용 비우기
        sortedChoices.forEach((choiceDiv) => {
            inputContainer.appendChild(choiceDiv);
        });
    }

    // 문제 정렬 및 화면에 다시 렌더링
    function sortAndRender() {
        // 문제 카드 컨테이너
        var cardContainer = document.getElementById("cardContainer");

        // 컨테이너의 자식 DIV들의 ID를 기준으로 오름차순 정렬
        var sortedForms = Array.from(cardContainer.children).sort((a, b) => {
            var idA = a.id; // ID 추출
            var idB = b.id;
            return idA.localeCompare(idB); // 문자열 비교로 정렬
        });

        // 정렬 후 Card 컨테이너를 갱신
        cardContainer.innerHTML = ''; // 기존 내용 비우기
        sortedForms.forEach((form) => {
            cardContainer.appendChild(form);
        });
    }

    // 선택지 삭제
    function removeInput(inputGroup, textInput, hiddenInput, hiddenInputValue, questionID, cardCount) {
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
                    var parentDiv = inputGroup.parentElement; // 부모 div 요소 가져오기
                    inputContainer.removeChild(parentDiv); // 부모 div 요소 제거

                    var index = usedValues[cardCount].indexOf(hiddenInputValue);
                    if (index !== -1) {
                        usedValues[cardCount].splice(index, 1);
                    }                    

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

    // 문제의 사용 가능한 가장 작은 Value 값을 찾아서 반환
    function findUnusedQuestion() {
        for (var value = 1; ; value++) {
            if (!cardArray.includes(value)) {
                return value;
                // 배열에 값이 없을 경우 1을 반환
            }
        }
    }

    // 문제 추가 시 Question + Choice 업데이트 
    function updateQuestion() {
        var formData = $("#question" + cardCount).serialize();
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
        //updateQuestion();
        cardCount = findUnusedQuestion();
        // cardCount = cardArray.length + 1;
        cardArray.push(cardCount);    

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
        
        var cardHtml = `
        <form id="question${cardCount}" enctype="multipart/form-data">
            <input type="hidden" name="questionID" value="${questionID}">
            <section id="Q${cardCount}">
                <div class="card mb-4">
                    <h5 class="card-header">⭐ <strong>${cardCount}</strong>번 문제</h5>
                    <input type="hidden" class="card-header form-control" name="number" value="${cardCount}">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="largeInput" class="form-label">문제를 여기에 적으세요 ✏️</label>
                            <textarea id="largeInput${cardCount}" class="form-control form-control-lg" name="name${cardCount}"></textarea>
                        </div>

                        <div class="mb-4">	
                            <label for="file" class="form-label">이미지 업로드 🖼️</label>
                            <input type="file" class="form-control" onchange="addFile(this);" multiple />
                            <div class="file-list">
                                <!-- 업로드한 이미지 목록이 여기에 동적으로 추가 -->
                            </div>
                            <!-- 응답 결과를 표시 -->
                            <div id="imgPreview"></div>
                        </div>

                        <div class="mt-2 mb-3">
                            <label for="largeSelect" class="form-label">어떤 형태의 문제인가요?</label>
                            <select id="largeSelect${cardCount}" class="form-select form-select-lg" name="gubun${cardCount}" onchange="showHideDiv(${cardCount}, ${questionID})">
                                <option>선택하세요.</option>
                                <option value="1">선택형</option>
                                <option value="2">서술형</option>
                                <option value="3">O/X</option>
                            </select>
                        </div>
                        <div id="hiddenDiv${cardCount}" style="display: none;">
                            <button type="button" id="addButton" class="mb-4 btn rounded-pill btn-primary" onclick="addInput(${cardCount}, ${questionID})">보기 추가</button>
                            <br>&nbsp;&nbsp;&nbsp;&nbsp;<label class="form-label">⬇️ 정답에 체크하세요.</label>
                            <div id="inputContainer${cardCount}"></div>
                        </div>
                        <div class="text-end mt-5 mb-3">
                            <button type="button" class="btn rounded-pill btn-danger" onclick="removeQuestion(${cardCount})">삭제</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
        `;


        // 새로운 카드를 cardContainer에 추가
        var cardContainer = document.getElementById("cardContainer");
        var newCard = document.createElement("div");
        newCard.id = `Q${cardCount}`;
        newCard.innerHTML = cardHtml;
        cardContainer.appendChild(newCard);

        // 동적으로 추가된 textarea에 대해 TinyMCE 초기화
        tinymce.init({
            selector: `#largeInput${cardCount}`,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            menubar: 'edit insert format table tools help',
            menu: {
                file: { title: 'File', items: 'newdocument restoredraft | preview | export print | deleteallconversations' },
                edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
                view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments' },
                insert: { title: 'Insert', items: 'image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime' },
                format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat' },
                tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount' },
                table: { title: 'Table', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' },
                help: { title: 'Help', items: 'help' }
            },
            toolbar: 'fontsize bold italic underline strikethrough forecolor backcolor | table charmap | align lineheight | numlist bullist | emoticons | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            relative_urls: false,
            remove_script_host: false,
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            height: 250,
            language: 'ko_KR',
        });

        var selectElement = newCard.querySelector(`#largeSelect${cardCount}`);
        selectElement.addEventListener("change", function() {
            showHideDiv(cardCount)
        });

        // cardCount++;

        window.location.href = `#Q${cardCount}`;
    }

    // 문제 삭제 
    function removeQuestion(cardCount) {
        shouldShowWarning = false;

        var confirmation = confirm(cardCount + '번 문제를 삭제합니다.');

        if (confirmation) {
            var formID = "question" + cardCount; // 특정 form의 id

            if (formID === "question1") {
                alert('첫 번째 문제는 삭제할 수 없어요.')
                return;
            } else {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': csrfToken},
                    url: "{{ url('quiz/destroyQuestion') }}",
                    type: "DELETE",
                    data: { testID: testID, number: cardCount },
                    dataType: "json",
                    success: function(data) {
                        if (data.success === true) {
                            var question = document.getElementById(formID);
                            if (question) {
                                question.remove();
                                alert('문제를 삭제했습니다.');

                                // 배열에서 cardCount 제거
                                var indexToRemove = cardArray.indexOf(cardCount);
                                if (indexToRemove !== -1) {
                                    cardArray.splice(indexToRemove, 1);
                                    // cardCount = findUnusedQuestion();
                                }

                            } else {
                                alert('삭제할 문제를 찾을 수 없습니다.');
                            }
                        } else {
                            alert('문제 삭제 실패!');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("AJAX 오류: " + textStatus + " - " + errorThrown);
                    }
                });
            }
        }
        shouldShowWarning = true;
    }

    // 이 시험의 모든 문제+선택지 삭제
    function reset() {
        var confirmation = confirm("❗이 시험에서 생성된 모든 문제를 삭제합니다.");
        if (confirmation) {
            shouldShowWarning = false;
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
        count = cardArray.length;
        alert('현재 cardCount : ' + count);

        unUsedNumber = findUnusedQuestion();
        if ((count + 1) == unUsedNumber) {

        } else {
            if (!cardArray.includes(unUsedNumber)) {
                alert('오류!\n' + unUsedNumber + '번 문제가 없습니다.\n문제 생성을 눌러 문제를 만들어주세요.');
                return;
            }
        }


        for (var i = 1; i <= count; i++) {
            alert(cardArray[i-1] + "번 문제를 저장합니다..");

            // 폼 제출 전에 tinyMCE 내용을 업데이트
            tinymce.get('largeInput' + cardArray[i-1]).save(); // 에디터의 내용을 textarea에 적용
            
            var formData = $("#question" + cardArray[i-1]).serialize();
            
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

    function exit() {
        $('#modal2').modal('show');
    }

    function toList() {
        shouldShowWarning = false;
        window.location.href = "#";
    }

    function test() {
        console.log(cardCount);
        console.log(cardArray);
    }

	// // TinyMCE ↓
    // tinymce.init({
    //   selector: 'textarea',
    //   plugins: 'tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
    //   toolbar: 'undo redo | fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    //   tinycomments_mode: 'embedded',
    //   tinycomments_author: 'Author name',
	//   relative_urls: false,
	//   remove_script_host: false,
    //   mergetags_list: [
    //     { value: 'First.Name', title: 'First Name' },
    //     { value: 'Email', title: 'Email' },
    //   ],
    //   ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")), 
	// });
	// // TinyMCE ↑

</script>

@endsection()