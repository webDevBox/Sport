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
                        <div><h4 class="text-orange">Edit Profile</h4></div>
                    </div>
                    @if (Session::has('success'))
                    <p class="shower alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                    @endif
                    @if (Session::has('error'))
                        <p class="shower alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                    @endif
                    <div class="col-12">
                     <form action="{{ route('editProfile') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            {{-- Name --}}
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">First Name</label>
                                <input type="text" name="firstName" value="{{ $user->first_name }}" class="form-control" required>
                                @if ($errors->has('firstName'))<p style="color:red;">{{ $errors->first('firstName') }}</p>@endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Last Name</label>
                                <input type="text" name="lastName" value="{{ $user->last_name }}" class="form-control" required>
                                @if ($errors->has('lastName'))<p style="color:red;">{{ $errors->first('lastName') }}</p>@endif
                            </div>
                            {{-- Password --}}
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">Old Password</label>
                                <input type="text" name="oldPassword" placeholder="Enter Old Password" class="form-control">
                                @if ($errors->has('oldPassword'))<p style="color:red;">{{ $errors->first('oldPassword') }}</p>@endif
                            </div>
                            <div class="form-group col-md-6">
                                <label for="name" class="col-form-label">New Password</label>
                                <input type="text" name="newPassword" placeholder="Enter New Password" class="form-control">
                                @if ($errors->has('newPassword'))<p style="color:red;">{{ $errors->first('newPassword') }}</p>@endif
                            </div>
                           
                        </div>
                       
                        <div class="form-group">
                            <label for="logo" class="col-form-label">Profile Image</label>
                            <input type="file" name="image" data-show-remove="false" id="input-file-now" class="dropify" data-height="300" @if($user->image != null) data-default-file="{{ asset('files/'.$user->image) }}"
                            @else data-default-file="{{ asset('files/sport/no-img.png') }}" @endif>
                            @if ($errors->has('image'))<p style="color:red;">{{ $errors->first('image') }}</p>@endif
                        </div>
                        <input type="submit" name="submit" value="Update Profile" class="btn btn-primary btn-rounded">
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
