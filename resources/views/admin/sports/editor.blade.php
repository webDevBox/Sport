@extends('layouts.admin')

@section('styles')
 <!-- dropify CSS -->
 <link href="{{ asset('theme/assets/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">

                    <div class="header-title mb-4 col-12">
                        <div><h4 class="text-orange">Edit Sport</h4></div>
                    </div>
                    @if (Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }} shower">{{ Session::get('success') }}</p>
                    @endif
                    @if (Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }} shower">{{ Session::get('error') }}</p>
                    @endif

                    <div class="col-12">
                    <form action="{{ route('editSport') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $sport->id }}">
                        @if ($errors->has('id'))<p style="color:red;">{{ $errors->first('id') }}</p>@endif
                        <input type="hidden" id="image" name="image" value="">

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Name</label>
                                <input type="text" name="name" id="name" value="{{ $sport->name }}" class="form-control" required>
                                @if ($errors->has('name'))<p style="color:red;">{{ $errors->first('name') }}</p>@endif
                            </div>
                            
                           <div class="form-group col-md-6">
                                <label for="inputState" class="col-form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option @if($sport->status == 1) selected @endif value="1">Active</option>
                                    <option @if($sport->status == 0) selected @endif value="0">In Active</option>
                                </select>
                                @if ($errors->has('status'))<p style="color:red;">{{ $errors->first('status') }}</p>@endif

                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label for="logo" class="col-form-label">Logo</label>
                            <input type="file" name="logo" class="dropify" data-height="300" @if($sport->logo != null) data-default-file="{{ asset('files/'.$sport->logo) }}"
                            @else data-default-file="{{ asset('files/sport/no-img.png') }}" @endif>
                            @if ($errors->has('logo'))<p style="color:red;">{{ $errors->first('logo') }}</p>@endif
                        </div>
                        <input type="submit" class="btn btn-primary btn-rounded" value="Save changes" name="submit">
                    </form>
                    </div>

                
                </div>
            </div>
        </div> <!-- end row -->

    </div> <!-- container -->

</div> <!-- content -->

@endsection

@section('scripts')

<script>
$('.dropify').on('dropify.afterClear', function(){
    $('#image').val('del'); 
});
</script>

<script src="{{ asset('theme/assets/js/dropify.min.js')}}"></script>
        <script>
            // dropify JS
            $(document).ready(function(){
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove:  'Supprimer',
                        error:   'Désolé, le fichier trop volumineux'
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element){
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element){
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element){
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e){
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });
        </script>

        <script>
            setTimeout(
            function() 
            {
                $('.shower').attr('style','display:none;');
            }, 2000);
        </script>

@endsection
