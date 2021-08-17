@extends('layouts.admin')

@section('styles')
<!-- switchery -->
<link rel="stylesheet" href="{{ asset('theme/plugins/switchery/switchery.min.css') }}" />
@endsection

@section('content')

<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="header-title mb-4 col-12">
                        <div class="pull-left"><h4 class="text-orange">Sports List</h4></div>
                        <div class="pull-right"><a href="{{ route('createSport') }}"><button type="button" class="btn btn-primary btn-sm btn-rounded waves-light waves-effect pull-right"><i class="fa fa-plus mr-1"></i>Add New Sports</button></a></div>
                       {{-- <div class="pull-right">
                                <select id="status" name="status" class="form-control form-control-sm">
                                   <option selected value="1">Active</option>
                                   <option value="2">In Active</option>
                                </select>
                       </div> --}}
                       <div class="clearfix"></div>
                   </div>
                    @if (Session::has('success'))
                    <p class="alert {{ Session::get('alert-class', 'alert-success') }} shower">{{ Session::get('success') }}</p>
                    @endif
                    <p class="alert alert-success" id="success_status" style="display: none"></p>
                    @if (Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }} shower">{{ Session::get('error') }}</p>
                    @endif
                    <p class="text-muted font-14 m-b-30">
                    </p>
                        <table id="datatable" class="table table-bordered table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th width="10" scope="col">Status</th>
                                <th width="10" scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($activeSports as $activeSport)
                                <tr>
                                    <td>{{ $activeSport->name }}</td>
                                    <td>
                                    <label class="switch"> <input class="status_check" id="{{$activeSport->id}}" type="checkbox" @if($activeSport->status == 1) checked @endif> <span class="slider round"></span> </label>                                    </td>
                                    <td>Edit</td>
                                </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                </div>
            </div>
        </div> <!-- end row -->

    </div> <!-- container -->

</div> <!-- content -->

@endsection

@section('scripts')
<script>
    $(function () {
    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        order: [],
        ajax: "{{ route('sport') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ]
    });
});
</script>
<script>
    function myFunction(id,status){
    if (confirm('Are you sure you want to change status')) {
    $.ajax({
        type: "GET",
        dataType: "json",
        url: '{{ route("sportStatus") }}',
        data: {'id': id},
        success: function(data){
        $('#success_status').attr('style','display:block;');
            $('#success_status').html(data.success);
            setTimeout(
            function() 
            {
                $('#success_status').attr('style','display:none;');
            }, 2000);
        }
    });
    }
    else
    {
        location.reload();
    }
    }
</script>
 {{-- <script>
   $(".statusBar").on('change',function(){
    if (confirm('Are you sure you want to change status')) {
    var id = $(this).attr('id');
    var status = $(this).prop('checked') == true ? 1 : 2;
    $.ajax({
        type: "GET",
        dataType: "json",
        url: '{{ route("sportStatus") }}',
        data: {'status': status, 'id': id},
        success: function(data){
            $('#success_status').attr('style','display:block;');
            $('#success_status').html(data.success);
            setTimeout(
            function() 
            {
                $('#success_status').attr('style','display:none;');
            }, 2000);
        }
    });
    }
    else
    {
        location.reload();
    }
});
</script> --}}

<script>
    setTimeout(
    function() 
    {
        $('.shower').attr('style','display:none;');
    }, 2000);
</script>

@endsection
