<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('status')->latest()->paginate(10);
        return view('admin.dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = Status::all();
        
        return view('admin.dashboard.categories.create_edit', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
        'name' => 'required|max:255',
        'slug' => 'required|unique:categories,slug',
        'status_id' => 'required|exists:status,id',
    ];
        $messages = [
        'name.required'      => 'Category ka naam likhna zaroori hai.',
        'name.max'           => 'Naam 255 characters se zyada nahi hona chahiye.',
        'slug.required'      => 'Slug banana zaroori hai.',
        'slug.unique'        => 'Yeh slug pehle se maujood hai, kuch aur try karein.',
        'status_id.required' => 'Status select karna zaroori hai.',
        'status_id.exists'   => 'Select kiya gaya status invalid hai.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Category created successfully!']);
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
        $statuses = Status::all();
        return view('admin.dashboard.categories.create_edit', compact('category', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:categories,slug,' . $category->id,
            'status_id' => 'required|exists:statuses,id',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'description' => $request->description,
            'status_id' => $request->status_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Category updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted successfully!']);
    }
}
