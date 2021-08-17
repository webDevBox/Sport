@extends('layouts.admin')

@section('styles')
 <!-- dropify CSS -->
 <link href="{{ asset('theme/assets/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">

                    <div class="header-title mb-4 col-12">
                        <div><h4 class="text-orange">Create Push Notification</h4></div>
                    </div>
                    @if (Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                    @endif
                    <p class="alert alert-success" id="success_status" style="display: none"></p>
                    @if (Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                    @endif
                    <div class="col-12">
                     <form action="{{ route('store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                           <div class="form-group col-md-4">
                                <label for="inputState" class="col-form-label">Select Sport <span class="text-danger">*</span></label>
                                <select id="game" name="game" class="form-control" required>
                                    <option selected disabled>Select Sport</option>
                                    @foreach ($games as $game)
                                        <option value="{{ $game->id }}">{{ $game->name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('game'))<p style="color:red;">{{ $errors->first('game') }}</p>@endif
                            </div>
                           <div class="form-group col-md-4">
                                <label for="inputState" class="col-form-label">Select Team</label>
                                <select id="teams" name="team[]" class="form-control select2-multiple select" data-toggle="select2" multiple="multiple" data-placeholder="All Teams">
                                </select>
                                @if ($errors->has('team'))<p style="color:red;">{{ $errors->first('team') }}</p>@endif
                            </div>
                            <div class="form-group col-md-4">
                                <label for="Title" class="col-form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Enter Title" maxlength="200" class="form-control" id="Title"  parsley-trigger="change" required>
                                @if ($errors->has('title'))<p style="color:red;">{{ $errors->first('title') }}</p>@endif
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label for="Notification" class="col-form-label">Description <span class="text-danger">*</span></label>
                            <textarea id="emojionearea2" name="description" maxlength="200" class="form-control" rows="5" required>Notification text.. :smile:</textarea>
                            @if ($errors->has('description'))<p style="color:red;">{{ $errors->first('description') }}</p>@endif
                        </div>
                       
                       <div class="form-group">
                            <input type="submit" name="submit" value="Send" class="btn btn-primary btn-rounded">
                        </div>
                      
                        
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
    $('#game').change(function(){
        var game = $(this).val();
        $.ajax({
            url: "{{ route('getTeams') }}",
            type: "GET",
            data: {
                game: game
            },
            success: function(data) {
                $('#teams')
                    .find('option')
                    .remove()
                    .end();
                if (data['success'] !== undefined) {
                    $('#teams')
                        .append($("<option></option>")
                            .attr("value", "")
                            .text('All teams'));
                    for (let i = 0; i < data['success'].teams.length; i++) {
                        $('#teams')
                            .append($("<option></option>")
                                .attr("value", data['success'].teams[i].captain_id)
                                .text(data['success'].teams[i].name));
                    }
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
    $('.select').select2();
});
</script>

@endsection
