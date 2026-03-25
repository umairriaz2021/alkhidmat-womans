@extends('admin.layout')
@if(isset($template_data))
@section('title','Update Template')
@else
@section('title','Create Template')
@endif
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                
                
                    <form action="@if(isset($template_data)) {{route('admin.update.template',$template_data['id'])}} @else {{route('admin.add.templates')}} @endif" 
      method="POST" class="row forms-sample w-100">
    
    @csrf
    @if(isset($template_data))
     @method('PATCH')
    @endif

    <div class="col-xl-8 col-md-8 col-xs-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{(isset($template_data)) ? 'Update Template' : 'Create Template'}}</h4>

                {{-- Success Message --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Error Messages --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Form Fields --}}
                <div class="form-group">
                    <label for="temp_name">Name</label>
                    <input type="text" name="temp_name" id="temp_name" value="{{ (isset($template_data) && !empty($template_data['display_name'])) ? $template_data['display_name'] : '' }}" class="form-control" placeholder="Template Name"
                        value="">
                </div>
                <div class="form-group">
                    <label for="statues">Status</label>
                    <select name="temp_status" class="form-select mb-3">
                    @if(!empty($statuses))
                        @foreach($statuses as $status)
                            <option value="{{ $status['id'] }}" 
                             @if(isset($template_data) && $template_data['status_id'] == $status['id']) selected @endif
                            >
                                {{ ucfirst($status['name']) }}
                            </option>
                        @endforeach
                    @endif
                </select>
                </div>

               

                

                <div class="form-group">
                    <button type="submit" class="btn btn-primary me-2">{{(isset($template_data)) ? 'Update' : 'Submit'}}</button>
                </div>
            </div>
        </div>
    </div>

    

</form>

              </div>
            </div>
          </div>
@endsection