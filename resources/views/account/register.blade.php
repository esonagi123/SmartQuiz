<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>회원가입 : SmartQuiz</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/boxicons.css') }}" />
    <script src="https://kit.fontawesome.com/649102945e.js" crossorigin="anonymous"></script>
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/css/pages/page-auth.css') }}" />
    <!-- Helpers -->
    <script src="{{ asset('/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('/assets/js/config.js') }}"></script>

    <style>
        table {
        width: 100%;
        overflow-x: auto;
        border-collapse: collapse;
        margin: 0 auto;
        }

        table td {
        position: relative;
        }

        input[type="radio"] {
        position: absolute;
        opacity: 0;
        }

        .avatar-label {
        display: block;
        width: 100%;
        height: 100%;
        cursor: pointer;
        border: 5px solid transparent;
        box-sizing: border-box;
        }

        img {
        width: 70px;
        height: 70px;
        padding : 3px;
        object-fit: cover;
        }
        input[type="radio"]:checked + label {
        border-color: #007bff; /* 선택되었을 때의 테두리 색상 */
        }
    </style>
</head>

<body>
    <!-- Content -->

    <!-- Modal -->
    <div class="modal fade" id="agreeModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalScrollableTitle">개인정보 처리 방침 및 이용 약관</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>[개인정보처리방침]</strong></p>

            <p>SmartQuiz는 대한민국의 개인정보 보호법, 정보통신망법과 본 방침에 따라 개인정보를 처리하고 있습니다.</p>
            
            <p><strong>1. 정의</strong></p>
            <p>'개인정보'란 살아 있는 개인에 관한 정보로서 이름, 연락처, 영상 등을 통하여 개인을 알아볼 수 있는 정보를 의미합니다. 해당 정보만으로는 특정 개인을 알아볼 수 없더라도 다른 정보와 쉽게 결합하여 알아볼 수 있는 것도 포함합니다.</p>
            
            <p>예를 들어 개인정보가 포함되지 않은 게시물과 닉네임은 그 자체만으로 개인정보라 할 수 없으며, 이메일 주소, 전화번호 등과 같이 연관지어 개인을 특정할 수 있을만한 다른 정보들이 수집되어 있어야 합니다.</p>
            
            <p><strong>2. 수집</strong></p>
            <p><strong>1) 서비스 이용 과정에서 다음과 같은 개인정보가 자동으로 생성되어 수집될 수 있습니다.</strong></p>
            <p>항목 : IP 주소, 접속일시, 서비스 이용 기록</p>
            <p>주목적 : 서비스 제공 및 부정 이용 방지</p>
            <p>보관기간 : 삭제 전 까지</p>
            
            <p><strong>2) 회원가입시 본 방침에 대한 동의 절차 후 다음과 같은 개인정보를 수집합니다.</strong></p>
            <p>항목 : 아이디, 이메일 주소, * 비밀번호, 닉네임</p>
            <p>주목적 : 서비스 이용</p>
            <p>보관기간 : 탈퇴 전 까지</p>
            
            <p style="color:red;">* 별표에 해당하는 항목은 그 누구도 알 수 없도록 복호화가 불가능한 단방향 암호화 처리 후 해시값만 보관됩니다.</p>
            
            <p><strong>3. 이용</strong></p>
            <p>수집된 개인정보는 보관기간 동안 주목적 외에도 다음과 같은 목적으로 이용될 수 있습니다.</p>
            
            <p><strong>1) 이용자 보호</strong></p>
            <p>본 방침 개정 등의 고지사항 전달</p>
            <p>계약이행을 위한 연락</p>
            <p>분쟁조정을 위한 기록 보존</p>
            <p>민원 및 분쟁 처리</p>
            
            <p><strong>2) 기타</strong></p>
            <p>서비스 개선 및 신규 서비스 개발</p>
            <p>프라이버시 보호 측면의 서비스 환경 구축</p>
            
            <p><strong>4. 보호</strong></p>
            <p>수집된 개인정보는 훼손되거나 유출되지 않도록 철저하게 보호되고 있습니다.</p>
            <p>항상 암호화하여 전송하고 있습니다.</p>
            <p>항상 지정된 디바이스에만 보관하고 있습니다.</p>
            <p>보안 업데이트와 백업을 주기적으로 실시하고 있습니다.</p>
            
            <p><strong>5. 파기</strong></p>
            <p>수집된 개인정보는 보관기간이 만료되면 다음과 같은 방법에 따라 즉시 파기됩니다.</p>
            <p>데이터 파일 : 복구 및 재생이 불가능한 기술적 방법으로 삭제</p>
            <p>다만, 이용자가 보관기간에 대하여 별도의 동의를 했거나 대한민국 법령에 의거 보관의무가 발생했을 경우에 한하여 일정기간 동안 파기되지 않을 수 있습니다. 또한 일부 개인정보는 *암호화되어 서비스 부정이용 방지를 위하여 다음과 같이 별도로 보관될 수 있습니다.</p>
            
            <p>*이메일 주소가 포함된 징계기록 등의 부정이용기록은 탈퇴 시점으로 부터 1년간 보관되어 재가입 방지에 이용될 수 있습니다.</p>
            <p>규정위반 등으로 징계받기 전 탈퇴와 재가입을 반복하며 부정이용하는 사례를 방지하기 위하여 탈퇴한 이용자의 *이메일 주소는 탈퇴 시점으로 부터 3개월간 보관되어 부정이용기록 작성에 이용될 수 있습니다.</p>
            <p>* 별표에 해당하는 항목은 그 누구도 알 수 없도록 복호화가 불가능한 단방향 암호화 처리 후 해시값만 보관됩니다.</p>
            
            <p><strong>6. 권리</strong></p>
            <p>이용자는 언제든지 회원정보 페이지에서 자신의 개인정보를 조회하거나 수정할 수 있으며, 회원탈퇴 페이지를 통하여 본 방침에 대한 동의를 철회할 수 있습니다. 최고 관리자에게 요청할 수도 있습니다.</p>
            
            <p>아래의 최고 관리자는 이용자의 개인정보를 보호할 의무가 있으며, 이용자는 최고 관리자에게 자신의 개인정보 처리를 요청할 권리가 있습니다.</p>
            
            <p>이름 : 김대현</p>
            <p>이메일 : esonagi123@naver.com</p>
            
            <p><strong>7. 개정</strong></p>
            <p>본 방침이 변경될 경우 공지사항을 통하여 사전 고지될 것입니다. 다만, 수집하는 개인정보의 항목, 이용목적의 변경 등과 같이 이용자 권리의 중대한 변경이 발생했을 경우 최소 30일 전에 고지될 것입니다. 시행되기 전 회원탈퇴 페이지를 통하여 거부권을 행사할 수 있으며, 그러지 않을 경우 동의한 것으로 봅니다.</p>
            
            <p>공고일자 : 2023년 10월 22일</p>
            <p>시행일자 : 2023년 10월 22일</p>

            <p><strong>[이용 약관]</strong></p>
            <p>준비 중..</p>
                    
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
            {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
          </div>
        </div>
      </div>
    </div>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href={{ url('/') }} class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs>
                                            <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                                            <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                                            <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                                            <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                                        </defs>
                                        <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                                <g id="Icon" transform="translate(27.000000, 15.000000)">
                                                    <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                        <mask id="mask-2" fill="white">
                                                            <use xlink:href="#path-1"></use>
                                                        </mask>
                                                        <use fill="#696cff" xlink:href="#path-1"></use>
                                                        <g id="Path-3" mask="url(#mask-2)">
                                                            <use fill="#696cff" xlink:href="#path-3"></use>
                                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                        </g>
                                                        <g id="Path-4" mask="url(#mask-2)">
                                                            <use fill="#696cff" xlink:href="#path-4"></use>
                                                            <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                        </g>
                                                    </g>
                                                    <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                                        <use fill="#696cff" xlink:href="#path-5"></use>
                                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <span class="app-brand-text demo text-body fw-bolder">SmartQuiz</span>
                            </a>
                        </div>

                        @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endforeach
                        @endif

                        <!-- /Logo -->
                        <h4 class="mb-2">회원가입 🚀</h4>
                        <p class="mb-4"></p>

                        <form id="formAuthentication" class="mb-3" action="{{ url('register/join') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="uid" class="form-label">❗ 아이디</label>
                                <input type="text" value="{{ old('uid') }}" class="form-control" id="uid" name="uid" placeholder="" autofocus />
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">❗ 이메일</label>
                                <input type="email" value="{{ old('email') }}" class="form-control" id="email" name="email" placeholder="" />
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">❗ 비밀번호</label>&nbsp;&nbsp;
                                <span class="badge bg-label-primary"><i class="fa-solid fa-lock"></i>&nbsp;단방향 암호화 되어 안전하게 저장됩니다.</span>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3 form-password-toggle">

                                <label class="form-label" for="password">❗ 비밀번호 확인</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="nickname" class="form-label">❗ 닉네임</label>
                                <input type="text" value="{{ old('nickname') }}" class="form-control" id="nickname" name="nickname" placeholder="" autofocus />
                            </div>

                            <div class="mb-4" style="overflow-x: auto;">
                                <label for="" class="form-label">프로필 아바타</label>
                                <table class="text-center">
                                    <tr>
                                      <td>
                                        <input type="radio" name="avatar" id="avatar0" value="0" @if (!old('avatar')) checked @endif>
                                        <label class="avatar-label" for="avatar0"><img src="{{ url('assets/img/avatars/avatar0.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="1" id="avatar1" @if (old('avatar') == 1) checked @endif>
                                        <label class="avatar-label" for="avatar1"><img src="{{ url('assets/img/avatars/avatar1.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="2" id="avatar2" @if (old('avatar') == 2) checked @endif>
                                        <label class="avatar-label" for="avatar2"><img src="{{ url('assets/img/avatars/avatar2.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="3" id="avatar3" @if (old('avatar') == 3) checked @endif>
                                        <label class="avatar-label" for="avatar3"><img src="{{ url('assets/img/avatars/avatar3.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="4" id="avatar4" @if (old('avatar') == 4) checked @endif>
                                        <label class="avatar-label" for="avatar4"><img src="{{ url('assets/img/avatars/avatar4.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="5" id="avatar5" @if (old('avatar') == 5) checked @endif>
                                        <label class="avatar-label" for="avatar5"><img src="{{ url('assets/img/avatars/avatar5.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="6" id="avatar6" @if (old('avatar') == 6) checked @endif>
                                        <label class="avatar-label" for="avatar6"><img src="{{ url('assets/img/avatars/avatar6.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="7" id="avatar7" @if (old('avatar') == 7) checked @endif>
                                        <label class="avatar-label" for="avatar7"><img src="{{ url('assets/img/avatars/avatar7.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="8" id="avatar8" @if (old('avatar') == 8) checked @endif>
                                        <label class="avatar-label" for="avatar8"><img src="{{ url('assets/img/avatars/avatar8.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="9" id="avatar9" @if (old('avatar') == 9) checked @endif>
                                        <label class="avatar-label" for="avatar9"><img src="{{ url('assets/img/avatars/avatar9.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="10" id="avatar10" @if (old('avatar') == 10) checked @endif>
                                        <label class="avatar-label" for="avatar10"><img src="{{ url('assets/img/avatars/avatar10.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="11" id="avatar11" @if (old('avatar') == 11) checked @endif>
                                        <label class="avatar-label" for="avatar11"><img src="{{ url('assets/img/avatars/avatar11.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="12" id="avatar12" @if (old('avatar') == 12) checked @endif>
                                        <label class="avatar-label" for="avatar12"><img src="{{ url('assets/img/avatars/avatar12.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="13" id="avatar13" @if (old('avatar') == 13) checked @endif>
                                        <label class="avatar-label" for="avatar13"><img src="{{ url('assets/img/avatars/avatar13.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="14" id="avatar14" @if (old('avatar') == 14) checked @endif>
                                        <label class="avatar-label" for="avatar14"><img src="{{ url('assets/img/avatars/avatar14.png') }}"></label>
                                      </td>
                                      <td>
                                        <input type="radio" name="avatar" value="15" id="avatar15" @if (old('avatar') == 15) checked @endif>
                                        <label class="avatar-label" for="avatar15"><img src="{{ url('assets/img/avatars/avatar15.png') }}"></label>
                                      </td>                                      
                                    </tr>
                                    <!-- Repeat the structure for additional rows -->
                                  </table>                               
                            </div>

                            <div class="mb-3">
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="agree" name="agree" />
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#agreeModal">개인정보 보호정책 및 이용약관</a>에 동의합니다.
                              </div>
                            </div>

                            <button class="btn btn-primary d-grid w-100" id="submitBtn" type="submit">회원가입</button>
                        </form>

                        <p class="text-center">
                            <span>이미 회원이신가요?</span>
                            <a href="{{ url('login') }}">로그인</a>
                        </p>
                    </div>
                </div>
                <!-- Register Card -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('/assets/js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('label').forEach(function(label) {
            label.addEventListener('click', function() {
                this.previousElementSibling.click();
            });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('input[name="avatar"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                    console.log('라디오가 체크되었습니다:', this.id);
                    }
                });
            });
        });

        const agreeCheckbox = document.getElementById('agree');
        const formAuthentication = document.getElementById('formAuthentication');
        const submitBtn = document.getElementById('submitBtn');

        formAuthentication.addEventListener('submit', (event) => {
          if (!agreeCheckbox.checked) {
            event.preventDefault(); // 폼 제출을 중지합니다.
            alert('개인정보 보호정책 및 이용약관에 동의해야 합니다.'); // 사용자에게 경고 메시지를 표시할 수 있습니다.
          }
        });
    </script>

</body>

</html>