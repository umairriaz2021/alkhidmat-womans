<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Status;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with(['category','profileImage','status'])->latest()->paginate(10);
        //echo "<pre>"; print_r($posts[0]->profileImage->file_path);die;
        return view('admin.dashboard.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $statuses = Status::all();
        $tags = Tag::all();
        return view('admin.dashboard.posts.create', compact('categories', 'statuses', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
    'title'       => 'required|max:255',
    'content'     => 'required',
    'category_id' => 'required|exists:categories,id',
    'status_id'   => 'required|exists:status,id',
    'image_id'    => 'nullable|exists:media,id', // Based on your media picker setup
], [
    // Title Messages
    'title.required'       => 'The post title is required.',
    'title.max'            => 'The title may not be greater than 255 characters.',
    
    // Content Messages
    'content.required'     => 'Please provide some content for your post.',
    
    // Category Messages
    'category_id.required' => 'Please select a category for this post.',
    'category_id.exists'   => 'The selected category is invalid.',
    
    // Status Messages
    'status_id.required'   => 'Please select a post status.',
    'status_id.exists'     => 'The selected status is invalid.',
    
    // Image Messages (Media Picker)
    'image_id.exists'      => 'The selected featured image does not exist in our records.',
]);

if ($validator->fails()) {
    return response()->json([
        'success' => false, 
        'errors'  => $validator->errors()
    ], 422);
}

        $post = new Post();
        $post->user_id = auth()->id(); // Logged in user ki ID
        $post->category_id = $request->category_id;
        $post->status_id = $request->status_id;
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->image_id = $request->image_id;
        $post->meta_title  = $request->meta_title;
        $post->meta_description = $request->meta_description;
        

        $post->save();

        // Many-to-Many tags attach karna
        if ($request->tags) {
            $post->tags()->attach($request->tags);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::with(['profileImage'])->find($id);
        $categories = Category::all();
        $statuses = Status::all();
        $tags = Tag::all();
        return view('admin.dashboard.posts.create', compact('post', 'categories', 'statuses', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $rules = [
        'title'       => 'required|max:255',
        'category_id' => 'required|exists:categories,id',
        'status_id'   => 'required|exists:status,id',
    ];
    $messages = [
        'title.required'       => 'Please enter a title for the post.',
        'title.max'            => 'The title is too long. Maximum 255 characters allowed.',
        'category_id.required' => 'Please select a category.',
        'category_id.exists'   => 'The selected category does not exist.',
        'status_id.required'   => 'Please select a status (e.g., Published or Draft).',
        'status_id.exists'     => 'The selected status is invalid.',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422); // 422 is the standard status for validation errors
    }
        $post = Post::find($id);
        $post->title = $request->title;
        $post->slug = Str::slug($request->title);
        $post->content = $request->content;
        $post->category_id = $request->category_id;
        $post->status_id = $request->status_id;
         $post->meta_title  = $request->meta_title;
         $post->image_id = $request->image_id;
        $post->meta_description = $request->meta_description;
        $post->save();

        // Tags sync karna (purane hatakar naye lagana)
        if ($request->tags) {
            //$post->tags()->attach($request->tags);
            $post->tags()->sync($request->tags);
        }

         return response()->json([
            'success' => true,
            'message' => 'Post updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $post = Post::find($id);

    if (!$post) {
        return response()->json([
            'success' => false,
            'message' => 'Post not found!'
        ], 404);
    }

    // Post delete karein
    $post->delete();

    // AJAX ke liye JSON response bhejein
    return response()->json([
        'success' => true,
        'message' => 'Post deleted successfully!'
    ]);
    }
}
