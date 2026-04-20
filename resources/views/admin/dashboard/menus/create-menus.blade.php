@extends('admin.layout')
@section('title','Create Menus')
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                
                
                    <form action="@if(isset($menu_data)) {{route('admin.update.menu',$menu_data['id'])}} @else {{route('admin.create.menus')}} @endif" 
      method="POST" class="row forms-sample w-100">
    
    @csrf
    @if(isset($menu_data))
        @method('PATCH')
    @endif
    <div class="col-xl-8 col-md-8 col-xs-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Add Menu</h4>

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
                    <label for="ln">Link Name</label>
                    <input type="text" name="ln" value="{{(isset($menu_data) && $menu_data['title']) ? $menu_data['title'] : ''}}"  id="ln" class="form-control" placeholder="Link Name"
                        value="">
                </div>

                <div class="form-group">
                    <label for="lu">Link URL</label>
                    <select class="form-select mb-3" name="lu">
                         <option value="#">Select URL</option>
                               @if(!empty($pages)) 
                              
                               @foreach($pages as $page) 
                                    <option value="{{$page['slug']}}" @if(isset($menu_data) && $menu_data['url'] == $page['slug']) selected @endif>
                                        {{ucfirst($page['title'])}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                </div>
                
                <div class="form-group">
                     <label for="parent_id">Submenu</label>
                     <select id="parent_id" class="form-select mb-3" name="parent_id">
                         <option value="">Select Parent</option>
                               @if(!empty($menus)) 
                              
                               @foreach($menus as $menu) 
                                    <option value="{{$menu['id']}}" @if(isset($menu_data) && $menu['id'] == $menu_data['parent_id']) selected @endif>
                                        {{ucfirst($menu['title'])}}
                                    </option>
                                @endforeach
                                @endif
                            </select>
                </div>
                <div class="form-group">
                    <label for="mega_menus_id">Mega Menus</label>
                    <select id="mega_menus_id" class="form-select mb-3" name="mega_menus_id[]" multiple>
                    <option value="">Select Mega Menu</option>
                    
                    @if(!empty($megaMenu)) 
                        @foreach($megaMenu as $mega) 
                            @php
                                // Check karna ke kya ye ID selected array mein maujood hai
                                $isSelected = false;
                                if(isset($menu_data) && !empty($menu_data['mega_menus_id'])) {
                                    // Agar data string hai (JSON), to usay array mein convert karein
                                    $savedIds = is_array($menu_data['mega_menus_id']) 
                                                ? $menu_data['mega_menus_id'] 
                                                : json_decode($menu_data['mega_menus_id'], true);
                                                
                                    if(in_array($mega['id'], (array)$savedIds)) {
                                        $isSelected = true;
                                    }
                                }
                            @endphp

                            <option value="{{ $mega['id'] }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ ucfirst($mega['group_name']) }}
                            </option>
                        @endforeach
                    @endif
                </select>
                </div>
                

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Post Status</div>
                <select name="post_status" class="form-select mb-3">
                    @if(!empty($statuses))
                        @foreach($statuses as $status)
                            <option value="{{ $status['id'] }}" @if(isset($menu_data) && $menu_data['status_id'] == $status['id']) selected  @endif>
                                {{ ucfirst($status['name']) }}
                            </option>
                        @endforeach
                    @endif
                </select>

                <button type="submit" class="btn btn-primary me-2">Submit</button>
            </div>
        </div>
    </div>

</form>

              </div>
            </div>
          </div>
@endsection
