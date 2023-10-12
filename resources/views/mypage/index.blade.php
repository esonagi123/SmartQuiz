
@extends('base')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">마이페이지</h4>

  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills flex-column flex-md-row mb-3">
        <li class="nav-item">
          <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>내 정보</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages-account-settings-notifications.html"
            ><i class="bx bx-bell me-1"></i>알림 설정</a
          >
        </li>
        <li class="nav-item">
          <a class="nav-link" href="pages-account-settings-connections.html"
            ><i class="bx bx-link-alt me-1"></i>계정 연결</a
          >
        </li>
      </ul>
      <div class="card mb-4">
        <h5 class="card-header">프로필 아바타</h5>
        <!-- Account -->
        <div class="card-body">
          <div class="d-flex align-items-start align-items-sm-center gap-4">
            <img src="{{ asset('/assets/img/avatars/avatar' . $userData['avatar'] .'.png') }}" class="d-block rounded" height="100" width="100"/>
          </div>
        </div>
        <hr class="my-0" />
        <div class="card-body">
          <form id="formAccountSettings" method="POST" onsubmit="return false">
            <div class="row">
              <div class="mb-3">
                <label for="uid" class="form-label">아이디</label>
                <input type="text" class="form-control" id="uid" value="{{ $userData['uid'] }}" readonly>
                <div id="defaultFormControlHelp" class="form-text">
                  아이디는 변경할 수 없습니다.
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
                <label for="defaultFormControlInput" class="form-label">닉네임</label>
                <input type="text" class="form-control" id="defaultFormControlInput" value="{{ $userData['nickname'] }}" aria-describedby="defaultFormControlHelp">
                <div id="defaultFormControlHelp" class="form-text">
                  We'll never share your details with anyone else.
                </div>
              </div>              
            </div>
            <div class="mb-3">
              <label for="defaultFormControlInput" class="form-label">비밀번호</label>
              <input type="password" class="form-control" id="defaultFormControlInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="defaultFormControlHelp">
              <div id="defaultFormControlHelp" class="form-text">
                We'll never share your details with anyone else.
              </div>
            </div>
            <div class="mb-3">
              <label for="defaultFormControlInput" class="form-label">비밀번호 확인</label>
              <input type="password" class="form-control" id="defaultFormControlInput" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="defaultFormControlHelp">
              <div id="defaultFormControlHelp" class="form-text">
                We'll never share your details with anyone else.
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