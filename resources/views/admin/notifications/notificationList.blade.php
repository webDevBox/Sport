@extends('layouts.admin')

@section('styles')
@endsection

@section('content')

<!-- Start Page content -->
<div class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="header-title mb-4 col-12">
                        <div class="pull-left"><h4 class="text-orange">Notifications List</h4></div>
                        <div class="pull-right">
                            @if($total > 0) <a href="{{ route('delete') }}"><button type="button" class="btn btn-danger btn-sm btn-rounded waves-light waves-effect pull-right"><i class="fa fa-trash-o mr-1"></i>Clear All</button></a> @endif
                            <a href="{{ route('create') }}"><button type="button" class="btn btn-primary btn-sm btn-rounded waves-light mr-2 waves-effect pull-right"><i class="fa fa-plus mr-1"></i>Send New Notification</button></a>                            
                        </div>
                        <div class="clearfix"></div>
                        @if (Session::has('success'))
                            <p class="alert {{ Session::get('alert-class', 'alert-success') }} shower">{{ Session::get('success') }}</p>
                            @endif
                            <p class="alert alert-success" id="success_status" style="display: none"></p>
                            @if (Session::has('error'))
                                <p class="alert {{ Session::get('alert-class', 'alert-danger') }} shower">{{ Session::get('error') }}</p>
                            @endif
                   </div>
                    <p class="text-muted font-14 m-b-30">
                    </p>
                        <table id="datatable" class="table table-bordered table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Sport</th>
                                <th scope="col">Teams</th>
                                <th scope="col">Date</th>
                            </tr>
                            </thead>
                            <tbody>
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
        ajax: "{{ route('notification') }}",
        columns: [
            {data: 'title', name: 'title',"width": "350px",},
            {data: 'body', name: 'body'},
            {data: 'sport', name: 'sport'},
            {data: 'teams', name: 'teams'},
            {data: 'created', name: 'created',"width": "150px",},
        ]
    });
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
