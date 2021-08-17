@extends('layouts.admin')

@section('styles')
@endsection



@section('content')
{{-- <div class="content-page"> --}}
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card-header">
                        <h4 class="card-title">Edit Template Options</h4>
                    </div>
                    <div class="card-box">
                            <form action="{{ route('templateoptionsUpdate', $icon->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name" class="col-form-label">Name</label>
                                        <input  type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $icon->name }}" id="" placeholder="Enter Icon Name">
                                  
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="templateName" class="col-form-label">Template Options <span class="text-danger">*</span></label>
                                        <select id="" name="templateName" class="form-control" >
                                            <option value="">Select Template Option</option>
                                            @foreach ($templates as $template)
                                           @if($template->id == $icon->template_id)
                                           <option value="{{$template->id}}" selected>{{$template->name}}
                                           </option>
                                           @else
                                           <option value="{{$template->id}}">{{$template->name}}
                                           </option>
                                           @endif
                                        @endforeach
                                        </select>
                                    </div>
                              
                               
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                                {{-- <a class="btn btn-light" href="{{ route('categoryUpdateCancel') }}">Cancel</a> --}}
                            </form>


                        </div>
                </div>
            </div>
        </div>
    </div>
{{-- </div> --}}
@endsection

@section('scripts')

@endsection
