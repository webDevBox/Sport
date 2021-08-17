@extends('layouts.admin')

@section('styles')
    {{--  <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">  --}}
@endsection

@section('content')
{{-- <div class="content-page"> --}}

    <div class="content">
        <div class="container-fluid">
            <div class="row">
              
            </div>
            <div class="row">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    <div class="card-header">
                        <h4 class="card-title">Template Options List</h4>
                    </div>
                    <div class="card-box">
                            <div id="datatable_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                    <table id="songsListTable" class="table table-bordered dt-responsive " style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                <tr>
                                    <th>Sr.#</th>
                                    <th>Name</th>
                                    {{-- <th>Created at</th>
                                    <th>Updated at</th> --}}
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($icons as $icon)
                                        <tr>
                                            <td>{{ $count++ }}</td>
                                            <td>{{ $icon->name }}</td>
                                          
                                            <td style="width: 95px;">
                                                <a href="{{ route('templateoptionsEdit', $icon->id) }}" class="btn btn-sm btn-icon waves-effect waves-light btn-warning "><i class="fa fa-edit"></i></a>
                                                <a href="{{ route('templateoptionsRemove', $icon->id) }}" class="btn btn-sm btn-icon waves-effect waves-light btn-warning "><i class="fa fa-trash"></i></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="ml-3">
                                    {{$icons->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Default Datatable
            $('#songsListTable').DataTable({
                "columnDefs": [
                { "orderable": false, "targets": [1] },
                ],
                "bPaginate": false,
                "aaSorting": [],
                "bFilter": true,
            });
        } );
    </script>
@endsection
