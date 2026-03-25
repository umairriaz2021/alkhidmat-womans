@props(['name','img_id'=>'', 'label' => 'Select Image','preview_path'=>'', 'id' => 'media_picker_' . rand(1, 999)])

<div class="mb-3" data-show="{{$preview_path}}">
    <label class="form-label fw-bold">{{ $label }}</label>
    <div class="media-picker-box border rounded p-2 d-flex align-items-center gap-3 bg-white shadow-sm">
        <div class="preview-container border rounded d-flex align-items-center justify-content-center" 
             id="preview_{{ $id }}" 
             style="width: 70px; height: 70px; background: #f8f9fa; overflow: hidden;">
             @if(!empty($preview_path))
             <img src="{{asset('storage/'.$preview_path)}}" style="width:100%; height:100%; object-fit:cover;">
             @else
             <i class="mdi mdi-image-plus text-muted h3 mb-0"></i>
             @endif
        </div>

        <div class="flex-grow-1">
            <input type="hidden" name="{{ $name }}" value="{{$img_id}}" id="input_{{ $id }}" class="target-input">
            <button type="button" 
                    class="btn btn-sm btn-outline-dark open-media-manager" 
                    data-target-input="#input_{{ $id }}" 
                    data-target-preview="#preview_{{ $id }}"
                    data-current-id="{{$img_id}}" 
                    data-bs-toggle="modal" 
                    data-bs-target="#mediaModal">
                Choose Image
            </button>
        </div>
    </div>
</div>