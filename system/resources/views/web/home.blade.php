@extends("web.layouts.app")

@section("content")
<div class="row">
  <div class="col-12">
    <div class="title-container">
      <h1 class="title text-center">Moderate</h1>
      <p class="text-center">Laravel Based Boilerplate</p>
    </div>
  </div>
</div>
@endsection

@push("styles")
<style>
  body{
    background: lightgray;
     font-family: 'Nunito';
  }

  .title{
    margin-bottom: 0;
  }

  .title-container{
    background: rgba(255, 255, 255, 0.8);
    border-radius: 5px;
    padding: 80px 20px;
    margin: 50px;
  }
</style>
@endpush