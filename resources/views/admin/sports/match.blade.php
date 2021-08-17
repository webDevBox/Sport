@extends('layouts.admin')

@section('styles')

@endsection

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="header-title mb-4 col-12">
                        <div><h4 class="text-orange">Matches List</h4></div>
                    </div>
                    @if (Session::has('success'))
                        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                    @endif
                    @if (Session::has('error'))
                        <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                    @endif
                    <table id="datatable" class="table table-bordered table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="thead-light">
                            <tr>
                              <th scope="col">Challanger</th>
                              <th scope="col">Opponent</th>
                              <th scope="col">Sports</th>
                              <th scope="col">Venue</th>
                              <th scope="col">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 
    </div>
</div> 


@endsection

@section('scripts')


<script type="text/javascript">
    $(function () {
      
      var table = $('#datatable').DataTable({
          processing: true,
          serverSide: true,
          order: [],
          ajax: "{{ route('match') }}",
          columns: [
              {data: 'Challanger', name: 'Challanger'},
              {data: 'Opponent', name: 'Opponent'},
              {data: 'Game', name: 'Game'},
              {data: 'Venue', name: 'Venue'},
              {data: 'Time', name: 'Time'},
          ]
      });
      
    });
  </script>

@endsection
