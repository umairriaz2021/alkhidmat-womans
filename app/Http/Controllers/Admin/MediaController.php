<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index() {
        $media = Media::latest()->get();
        return response()->json($media);
    }
    public function store(Request $request) {
        $request->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads/media', 'public'); // Storage/app/public/uploads/media

            $media = Media::create([
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return response()->json($media);
        }
    }
}
