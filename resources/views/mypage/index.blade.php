
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

@if (\Session::has('edit_error'))
  <script>
    $(document).ready(function() {
      $('#checkPassword').modal('show');
    });
  </script>
@elseif (\Session::has('destroy_error'))
  <script>
    $(document).ready(function() {
      $('#accountUnActivation').modal('show');
    });
  </script>
@endif

@if (\Session::has('success'))
  <script>
    alert("{!! \Session::get('success') !!}");;
  </script>
@endif

{{-- 아바타 변경 모달 --}}
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

{{-- 비밀번호 확인 모달 : 회원정보 수정 --}}
<div class="modal fade" id="checkPassword" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalCenterTitle">❗ 비밀번호 확인</h5>
          </div>
          <form method="post" action="{{ route('mypage.checkPassword') }}">
            @csrf
            <input type="hidden" name="type" value="edit">
            <div class="modal-body">
              <input type="hidden" name="id" value="{{ $userData['id'] }}">
              <label for="defaultFormControlInput" class="form-label">* 비밀번호를 입력하세요.</label>
              <input type="password" class="form-control" name="pwd" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
              <div id="defaultFormControlHelp" class="form-text" style="color: red;">
                @error('pwd')
                  {{ $message }}
                @enderror
                @if (\session()->has('error'))
                  {{ session('error') }}
                @endif
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="submit" class="btn btn-primary">확인</button>
            </div>
          </form>
      </div>
  </div>
</div>

{{-- 비밀번호 확인 모달 : 계정 삭제 --}}
<div class="modal fade" id="accountUnActivation" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modalCenterTitle">❗계정 삭제 - 비밀번호 확인</h5>
          </div>
          <form method="post" id="accountUnActivationForm" action="{{ route('mypage.checkPassword') }}">
            @csrf
            <input type="hidden" name="type" value="destroy">
            <div class="modal-body">
              <input type="hidden" name="id" value="{{ $userData['id'] }}">
              <label for="defaultFormControlInput" class="form-label">* 비밀번호를 입력하세요.</label>
              <input type="password" class="form-control" name="pwd" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;">
              <div id="defaultFormControlHelp" class="form-text" style="color: red;">
                @error('pwd')
                  {{ $message }}
                @enderror
                @if (\session()->has('error'))
                  {{ session('error') }}
                @endif
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                <button type="button" id="deleteConfirm" class="btn btn-primary" onclick="confirmAccountUnActivationForm()">확인</button>
            </div>
          </form>
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
            <div class="row">
              <div class="mb-3">
                <label for="uid" class="form-label">아이디</label>
                <input type="text" class="form-control" id="uid" value="{{ $userData['uid'] }}" readonly>
                <div id="defaultFormControlHelp" class="form-text">
                </div>
              </div>
              <div class="mb-3">
                <label for="defaultFormControlInput" class="form-label">이메일</label>
                <input type="email" class="form-control" id="defaultFormControlInput" value="{{ $userData['email'] }}" readonly>
                <div id="defaultFormControlHelp" class="form-text">
                </div>
              </div>
              <div class="mb-3">
                <label for="defaultFormControlInput" class="form-label">닉네임</label>
                <input type="text" class="form-control" id="defaultFormControlInput" value="{{ $userData['nickname'] }}" readonly>
                <div id="defaultFormControlHelp" class="form-text">
                </div>
              </div>
              <div class="mt-2 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#checkPassword">회원정보 수정</button>
              </div>                           
            </div>
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
            <div class="form-check mb-3">
            <div class="text-end">
              <button type="button" id="checkPasswordBtn" class="btn btn-danger deactivate-account" data-bs-toggle="modal" data-bs-target="#accountUnActivation">삭제</button>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function confirmAccountUnActivationForm() {
    // document.getElementById("deleteConfirm").addEventListener("click", function() {
    var confirmed = confirm("정말 탈퇴할까요? 😢");
    if (confirmed) {
        // "확인"을 클릭한 경우 폼을 제출합니다.
        document.getElementById("accountUnActivationForm").submit();
    } else {
      return; 
    }
  }
</script>

  @endsection()