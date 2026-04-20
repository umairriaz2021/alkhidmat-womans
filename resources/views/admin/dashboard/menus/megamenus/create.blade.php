@extends('admin.layout')
@section('title','Create Mega Menus')
@section('content')
<div class="content-wrapper">
            <div class="row">
            
              
              <div class="col-12 grid-margin stretch-card">
                
                
                   <form action="{{ isset($megaMenuData) ? route('admin.update.megamenus', $megaMenuData->id) : route('admin.create.megamenus') }}" 
      method="POST" class="row forms-sample w-100">
    
    @csrf
    @if(isset($megaMenuData))
        @method('PATCH')
    @endif
    <div class="col-xl-8 col-md-8 col-xs-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">{{ isset($megaMenuData) ? 'Edit' : 'Add' }} Mega Menus</h4>

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
                    <label for="ln">Group Name</label>
                    <input type="text" name="group_name" value="{{ old('group_name', $megaMenuData->group_name ?? '') }}"  id="ln" class="form-control" placeholder="Group Name"
                        value="">
                </div>

                
                
                <div class="form-group">
                     <label for="parent_id">All Menu Items</label>
                     <select id="parent_id" class="form-select mb-3" name="menu_id[]" multiple style="height:200px;">
    @foreach($menus as $menu) 
        {{-- Sirf wo menus dikhao jo child hain (parent_id null nahi hai) --}}
   
            @php
                $selected = '';
                if(isset($megaMenuData) && is_array($megaMenuData->links)){
                    if(in_array($menu['id'], $megaMenuData->links)){
                        $selected = 'selected';
                    }
                }
            @endphp
            <option value="{{ $menu['id'] }}" {{ $selected }}>
                {{ ucfirst($menu['title']) }} 
                {{-- Optional: Parent ka naam bhi dikha sakte hain pehchan ke liye --}}
                @if(isset($menu['parent']['title'])) 
                    ({{ ucfirst($menu['parent']['title']) }}) 
                @endif
            </option>
        
    @endforeach
</select>
                </div>
               
                

            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-xs-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">Status</div>
               <select name="post_status" class="form-select mb-3">
                    @foreach($statuses as $status)
                        <option value="{{ $status['id'] }}" 
                            {{ (old('post_status', $megaMenuData->status_id ?? '') == $status['id']) ? 'selected' : '' }}>
                            {{ ucfirst($status['name']) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary me-2">{{ isset($megaMenuData) ? 'Update' : 'Submit' }}</button>
            </div>
        </div>
    </div>

</form>

              </div>
            </div>
          </div>
@endsection
