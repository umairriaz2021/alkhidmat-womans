<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
   {

    $tags = Tag::latest()->paginate(10); 
    
    return view('admin.dashboard.tags.index', compact('tags'));
  }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.dashboard.tags.create_edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'required|unique:tags,slug'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Tag::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json(['success' => 'Tag created successfully!']);
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
        $tag = Tag::findOrFail($id);
        return view('admin.tags.create_edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'slug' => 'required|unique:tags,slug,' . $tag->id
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tag->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        return response()->json(['success' => 'Tag updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::find($id);

    if (!$tag) {
        return response()->json([
            'success' => false,
            'message' => 'Tag not found.'
        ], 404);
    }

    // Agar tag kisi post ke sath attach hai, to pivot table se bhi remove ho jayega agar cascade set hai
    $tag->delete();

    return response()->json([
        'success' => true,
        'message' => 'Tag deleted successfully!'
    ]);
    }
}
