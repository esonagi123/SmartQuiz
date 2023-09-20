@extends('base')

@section('content')

<div class="col-md-6">
  <div class="card mb-4">
    <h5 class="card-header">Float label</h5>
    <div class="card-body">
      <div class="form-floating">
        <input type="text" class="form-control" id="floatingInput" placeholder="John Doe" aria-describedby="floatingInputHelp">
        <label for="floatingInput">Name</label>
        <div id="floatingInputHelp" class="form-text">
          We'll never share your details with anyone else.
        </div>
      </div>
    </div>
  </div>
</div>

@endsection()