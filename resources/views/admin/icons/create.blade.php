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
                        <h4 class="card-title">Templates Options</h4>
                    </div>
                        <div class="card-box">
                            <h4 class="m-t-0 header-title">Create Template Options</h4>

                            <form action="{{ route('templateoptionsStore') }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="name" class="col-form-label">Name <span class="text-danger">*</span></label>
                                        <input  type="text" name="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="" placeholder="Enter Template Name" value="{{old('name')}}">                                  
                                    </div>


                                    <div class="form-group col-md-6">
                                        <label for="templateName" class="col-form-label">Templats<span class="text-danger">*</span></label>
                                        <select id="" name="templateName" class="form-control">
                                            <option value="">Select Template </option>
                                            @foreach ($templates as $template)
                                            <option value="{{$template->id}}">{{$template->name}}
                                            </option>
                                                @endforeach
                                        </select>            
                                    </div>   
                                    
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                                {{-- <a class="btn btn-light" href="{{ route('categoryCreateCancel') }}">Cancel</a> --}}
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
