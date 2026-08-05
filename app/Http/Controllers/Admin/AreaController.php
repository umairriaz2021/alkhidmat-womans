<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Category;
use App\Models\Status;
use App\Models\Media;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $areas = Area::with(['category','profileImage','status'])->latest()->paginate(10);
        //echo "<pre>"; print_r(json_decode(json_encode($areas,true)));die;
        return view('admin.dashboard.areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
     public function create()
    {
        $categories = Category::all();
        $statuses = Status::all();
        
        return view('admin.dashboard.areas.create', compact('categories', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

       
        $validator = Validator::make($request->all(), [
            'title'                      => 'required|string|max:255',
            'slug'                       => 'required|string|max:255|unique:areas,slug', // Table name verify kar lein
            'content'                    => 'nullable|string',
            'category_id'                => 'required|exists:categories,id',
            'status_id'                  => 'required|exists:status,id',
            'image_id'                   => 'nullable|string|max:255',
            'meta_title'                 => 'nullable|string|max:255',
            'meta_description'           => 'nullable|string',
            
            // Dynamic Repeaters Validation
            'our_services'               => 'nullable|array',
           

            'area_heading'               => 'nullable|string|max:255',
            'area_content'               => 'nullable|string',
            'area_quote'                 => 'nullable|string',
            'quote_button_text'          => 'nullable|string|max:255',
            'quote_url'                  => 'nullable|string|max:255',
           

            'gallery'                    => 'nullable|array',
            'gallery.*'                  => 'image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ], [
            // Custom English Error Messages
            'title.required'                     => 'The area title field is required.',
            'category_id.required'               => 'Please select a valid category.',
            'status_id.required'                 => 'Please select a post status.',
            'slug.unique'                        => 'This title/slug already exists. Please enter a unique title.',
            
            
           
            'gallery.*.image'                    => 'Uploaded gallery file must be a valid image.',
            'gallery.*.mimes'                    => 'Allowed gallery image formats are jpeg, png, jpg, webp, and svg.',
            'gallery.*.max'                      => 'Gallery images must not exceed 2MB in size.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }
        try{
             $post = new Area();
            $post->user_id          = auth()->id() ?? 1; // Logged-in User ID
            $post->category_id      = $request->category_id;
            $post->title            = $request->title;
            $post->status_id        = $request->status_id;
            $post->slug             = $request->slug;
            $post->content          = $request->content;
            $post->image_id         = $request->image_id;
            //$post->meta_title       = $request->meta_title;
            //$post->meta_description = $request->meta_description;
            $post->area_heading      = $request->area_heading;
            $post->area_content      = $request->area_content;
            $post->area_quote        = $request->area_quote;
            $post->quote_button_text = $request->quote_button_text;
            $post->quote_url         = $request->quote_url;
            $post->our_services     = $request->filled('our_services') ? json_encode(array_values($request->our_services)) : null;
            
            //$post->gallery          = !empty($galleryPaths) ? json_encode($galleryPaths) : null;
            $galleryMediaIds = [];
            if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    // Upload File to Storage
                    $path = $file->store('uploads/media', 'public');

                    // Create Entry in Media Table
                    $media = Media::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);

                    // Store created Media Path (or ID if you prefer: $media->id)
                    $galleryMediaIds[] = 'storage/' . $path;
                }
            }
        }    
            $post->gallery   = !empty($galleryMediaIds) ? json_encode($galleryMediaIds) : null;
            $post->save();
            return response()->json([
                'success' => true,
                'message' => 'Area created successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
       
            }  

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $post = Area::with(['profileImage'])->find($id);
        $categories = Category::all();
        $statuses = Status::all();
       
        return view('admin.dashboard.areas.create', compact('post', 'categories', 'statuses'));
    }



public function update(Request $request, string $id)
{
    // 1. Existing Record Find Karein
    
    $post = Area::findOrFail($id);
      
    // 2. Validation Rules
    $validator = Validator::make($request->all(), [
        'title'             => 'required|string|max:255',
        'slug'              => 'required|string|max:255|unique:areas,slug,' . $id,
        'content'           => 'nullable|string',
        'category_id'       => 'required|exists:categories,id',
        'status_id'         => 'required|exists:status,id',
        'image_id'          => 'nullable|string|max:255',
        'meta_title'        => 'nullable|string|max:255',
        'meta_description'  => 'nullable|string',

        // Dynamic Repeaters & Sections
        'our_services'      => 'nullable|array',
        'area_heading'      => 'nullable|string|max:255',
        'area_content'      => 'nullable|string',
        'area_quote'        => 'nullable|string',
        'quote_button_text' => 'nullable|string|max:255',
        'quote_url'         => 'nullable|string|max:255',

        // Existing Images Array
        'gallery_existing'  => 'nullable|array',

        // New Gallery Files Upload Validation
        'gallery'           => 'nullable|array',
        'gallery.*'         => 'image|mimes:jpeg,png,jpg,webp,svg|max:2048',
    ], [
        'title.required'        => 'The area title field is required.',
        'category_id.required'  => 'Please select a valid category.',
        'status_id.required'    => 'Please select a post status.',
        'slug.unique'           => 'This title/slug already exists. Please enter a unique title.',
        'gallery.*.image'       => 'Uploaded gallery file must be a valid image.',
        'gallery.*.mimes'       => 'Allowed gallery image formats are jpeg, png, jpg, webp, and svg.',
        'gallery.*.max'         => 'Gallery images must not exceed 2MB in size.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        // 3. Purani Gallery Fetch Karein (Cleanup comparison ke liye)
        $oldGallery = !empty($post->gallery) ? json_decode($post->gallery, true) : [];
        if (!is_array($oldGallery)) {
            $oldGallery = [];
        }

        // 4. Retained Images collect karein
        $galleryMediaIds = [];
        if ($request->has('gallery_existing') && is_array($request->gallery_existing)) {
            $galleryMediaIds = $request->gallery_existing;
        }

        // 5. Removed Images ko Storage & Media Table se Delete/Unlink Karein
        $removedImages = array_diff($oldGallery, $galleryMediaIds);

        foreach ($removedImages as $removedImgPath) {
            // Path string clean karein (e.g. 'storage/uploads/media/xyz.jpg' -> 'uploads/media/xyz.jpg')
            $relativePath = str_replace('storage/', '', $removedImgPath);

            // Physical File Storage se Delete Karein
            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }

            // Media Table se Entry Delete Karein
            Media::where('file_path', $relativePath)->delete();
        }

        // 6. Nayi Gallery Files Upload Karein & Media Record Create Karein
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    // Store File
                    $path = $file->store('uploads/media', 'public');

                    // Save in Media Model
                    Media::create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                    ]);

                    // Append Path
                    $galleryMediaIds[] = 'storage/' . $path;
                }
            }
        }

        // 7. Post Fields Update Karein
        $post->category_id       = $request->category_id;
        $post->title             = $request->title;
        $post->status_id         = $request->status_id;
        $post->slug              = $request->slug;
        $post->content           = $request->content;
        $post->image_id          = $request->image_id;
        $post->meta_title        = $request->meta_title;
        $post->meta_description  = $request->meta_description;
        $post->area_heading       = $request->area_heading;
        $post->area_content       = $request->area_content;
        $post->area_quote         = $request->area_quote;
        $post->quote_button_text  = $request->quote_button_text;
        $post->quote_url          = $request->quote_url;

        // JSON Encoding
        $post->our_services      = $request->filled('our_services') ? json_encode(array_values($request->our_services)) : null;
        $post->gallery           = !empty($galleryMediaIds) ? json_encode(array_values($galleryMediaIds)) : null;

        // 8. Save Updated Post
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Area updated successfully!'
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
