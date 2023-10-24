
@extends('base')

@section('content')
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

  .avatarImg {
  width: 70px;
  height: 70px;
  padding : 3px;
  object-fit: cover;
  }
  input[type="radio"]:checked + label {
  border-color: #007bff; /* 선택되었을 때의 테두리 색상 */
  }
</style>

<!-- Modal (data-bs-backdrop="static" : 안사라지게)-->
<div class="modal fade" id="modalCenter" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalCenterTitle">프로필 아바타 변경</h5>
          </div>
          <div class="modal-body mt-3">
            <form method="post" action="{{ url('/updateAvatar') }}">
              @csrf
              <input type="hidden" name="id" value="{{ $userData['id'] }}">
              <div class="mb-4">
                <div class="mb-4" style="overflow-x: auto;">
                <table class="text-center">
                  <tr>
                    @php
                      $avatars = range(0, 15);
                    @endphp
                    @foreach ($avatars as $avatar)
                      @if ($avatar == $userData['avatar'])
                        <td>
                          <input type="radio" name="avatar" id="avatar{{ $avatar }}" value="{{ $avatar }}" checked>
                          <label class="avatar-label" for="avatar{{ $avatar }}"><img class="avatarImg" src="{{ url('assets/img/avatars/avatar' . $avatar . '.png') }}"></label>
                        </td>
                      @else
                        <td>
                          <input type="radio" name="avatar" id="avatar{{ $avatar }}" value="{{ $avatar }}">
                          <label class="avatar-label" for="avatar{{ $avatar }}"><img class="avatarImg" src="{{ url('assets/img/avatars/avatar' . $avatar . '.png') }}"></label>
                        </td>
                      @endif
                    @endforeach
                </table>
                </div> 
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                  <button type="submit" class="btn btn-primary">적용</button>
              </div>
            </form>
          </div>
      </div>
  </div>
</div>

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">마이페이지</h4>

  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
          <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>내 정보</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="alert('준비 중..')"><i class="bx bx-bell me-1"></i>알림 설정</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="alert('준비 중..')"><i class="bx bx-link-alt me-1"></i>계정 연결</a>
        </li>
      </ul>
      <div class="card mb-4">
        <h5 class="card-header">프로필 아바타</h5>
        <!-- Account -->
        <div class="card-body">
          <div class="d-flex align-items-center align-items-sm-center gap-4">
            <img src="{{ asset('/assets/img/avatars/avatar' . $userData['avatar'] .'.png') }}" class="d-block rounded" height="100" width="100"/>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCenter">아바타 변경</button> 
          </div>
        </div>
        <hr class="my-0" />
        <div class="card-body">
          <form method="POST" action="">
            @csrf
            <input type="hidden" name="id" value="{{ $userData['id'] }}">
            <div class="row">
              <div class="mb-3">
                <label for="uid" class="form-label">아이디</label>
                <input type="text" class="form-control" id="uid" value="{{ $userData['uid'] }}" readonly>
                <div id="defaultFormControlHelp" class="form-text">
                  아이디는 변경할 수 없어요.
                </div>
              </div>
              <div class="mb-3">
                <label for="defaultFormControlInput" class="form-label">이메일</label>
                <input type="email" class="form-control" id="defaultFormControlInput" value="{{ $userData['email'] }}" aria-describedby="defaultFormControlHelp">
                <div id="defaultFormControlHelp" class="form-text">
                  We'll never share your details with anyone else.
                </div>
              </div>
              <div class="mb-3">
                <label for="defaultFormControlInput" class="form-label">닉네임 (2~8자 이내의 한글과 영문 대/소문자)</label>
                <input type="text" class="form-control" id="defaultFormControlInput" value="{{ $userData['nickname'] }}" aria-describedby="defaultFormControlHelp">
                <div id="defaultFormControlHelp" class="form-text">
                  에러메시지
                </div>
              </div>              
            </div>
            <div class="mb-3">
              <label for="defaultFormControlInput" class="form-label">비밀번호 (8~16자 이내의 소문자+숫자)</label>
              <input type="password" class="form-control" id="defaultFormControlInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="defaultFormControlHelp">
              <div id="defaultFormControlHelp" class="form-text">
                에러메시지
              </div>
            </div>
            <div class="mb-3">
              <label for="defaultFormControlInput" class="form-label">비밀번호 확인</label>
              <input type="password" class="form-control" id="defaultFormControlInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="defaultFormControlHelp">
              <div id="defaultFormControlHelp" class="form-text">
              </div>
            </div>                  
            <div class="mt-2 text-end">
              <button type="submit" class="btn btn-primary me-2">변경사항 저장</button>
            </div>
          </form>
        </div>
        <!-- /Account -->
      </div>
      <div class="card">
        <h5 class="card-header">계정 삭제</h5>
        <div class="card-body">
          <div class="mb-3 col-12 mb-0">
            <div class="alert alert-warning">
              <h6 class="alert-heading fw-bold mb-1">정말 계정을 삭제하시겠습니까?</h6>
              <p class="mb-0">생성된 모든 퀴즈가 삭제되고 되돌릴 수 없습니다.</p>
            </div>
          </div>
          <form id="formAccountDeactivation" onsubmit="return false">
            <div class="form-check mb-3">
              <input
                class="form-check-input"
                type="checkbox"
                name="accountActivation"
                id="accountActivation"
              />
              <label class="form-check-label" for="accountActivation"
                >확인했습니다.</label
              >
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-danger deactivate-account">삭제</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


  @endsection()