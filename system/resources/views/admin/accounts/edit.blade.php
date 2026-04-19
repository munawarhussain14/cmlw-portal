@extends('admin.layouts.app')

@section('styles')
 <!-- summernote -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.css')}}">
@endsection

@section('scripts')
<!-- Summernote -->
<script src="{{asset('assets/admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
  $(function () {
    // Summernote
    $('#summernote').summernote();

    $('input[type="file"]').change(function() {
        var fileName = this.files[0].name;
        $(this).parent().find(".custom-file-label").text(fileName);
    });
  })
</script>
@endsection

@section("content")
<div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">News</h3>
                {{-- @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="{{route('admin.news.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                    id="title" 
                    name="title"
                    placeholder="Enter Title"
                    value="{{ old('title') }}"
                    />
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="inputDescription">Description</label>
                    <textarea id="summernote" name="description">
                        {{ old('description') }}
                    </textarea>
                   @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Attachment</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input 
                        type="file" 
                        name="attachment" 
                        accept="application/pdf" 
                        value="{{ old('attachment') }}" 
                        class="custom-file-input" 
                        id="attachment">
                        <label class="custom-file-label" for="attachment">{{ old('attachment',"Choose file") }}</label>
                      </div>
                      {{-- <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div> --}}
                    </div>
                    @error('attachment')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                <div class="form-group">
                    <label for="exampleInputFile">Featured</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input 
                        type="file" 
                        accept="image/apng, image/avif, image/gif, image/jpeg, image/png, image/svg+xml, image/webp" 
                        class="custom-file-input" 
                        value="{{ old('featured') }}"
                        name="featured" 
                        id="featured">
                        <label class="custom-file-label" for="featured">Choose file</label>
                      </div>
                      {{-- <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div> --}}
                    </div>
                    @error('featured')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-check">
                    <input type="checkbox" name="status" class="form-check-input" id="status">
                    <label class="form-check-label" for="status">Publish</label>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
@endsection