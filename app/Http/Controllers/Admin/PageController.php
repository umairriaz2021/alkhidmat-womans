<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Status;
use App\Models\Slider;
use App\Models\PageTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;

class PageController extends Controller
{
    public function index(){
      
       $pages = Page::with(['profileImage','template'])->get();
       $pages = json_decode(json_encode($pages),true);
       
       return view('admin.dashboard.pages.index',compact('pages'));
    }

    public function createPages(Request $request)
{
    $statuses = Status::get()->toArray();
    $sliders = Slider::get()->toArray();
    $templates = PageTemplate::get()->toArray();

    if ($request->isMethod('post')) {
        $rules = [
            'p_title'     => 'required|string|max:255',
            'slug'        => 'required|string|unique:pages,slug',
            'template_id' => 'required|exists:page_templates,id',
            'post_status' => 'required|integer',
            'bcat'        => 'nullable|array',
        ];

        $messages = [
            'p_title.required'     => 'The page title is required.',
            'slug.required'        => 'The slug could not be generated. Please check the title.',
            'slug.unique'          => 'This slug is already in use for another page.',
            'template_id.required' => 'Selecting a template is mandatory.',
            'post_status.required' => 'Please select a post status.',
            'bcat.array'           => 'The format of the selected slides is invalid.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction(); // Transaction start karein

            $page = new \App\Models\Page();
            
            $page->user_id          = auth()->id() ?? 1;
            $page->title            = $request->p_title;
            $page->slug             = $request->slug;
            $page->content          = $request->p_content;
            $page->meta_title       = $request->mtitle;
            $page->meta_description = $request->meta_desc;
            $page->template_id      = $request->template_id;
            $page->status_id        = $request->post_status;
            $page->image_id         = $request->profile_image_id; // Rename column image_id

            // --- Nayi Logic: bcat array ko Page table mein save karna ---
            if ($request->has('bcat') && is_array($request->bcat)) {
                // Agar column JSON hai (Recommended):
                $page->slider_id = json_encode($request->bcat);
                
                // Agar column String/Text hai (Old way):
                // $page->slider_id = implode(',', $request->bcat);
            } else {
                $page->slider_id = null;
            }

            $page->save();

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Page created successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    return view('admin.dashboard.pages.create', compact('statuses', 'sliders', 'templates'));
}

  public function editPage($id)
  {
      $page = Page::with(['profileImage','template'])->find($id)->toArray();
      //echo "<pre>"; print_r($page);die;
      $sliderIds = json_decode($page['slider_id'],true);
      $statuses = Status::get()->toArray();
      $sliders = Slider::get()->toArray();
    $templates = PageTemplate::get()->toArray();

    //   $sliders = Slider::whereIn('id', $sliderIds)
    //         ->orderByRaw("FIELD(id, ".implode(',', $sliderIds).")")
    //         ->select('id','main_heading')
    //         ->get()->toArray();
      return view('admin.dashboard.pages.create',compact('page','statuses','sliders','templates'));
  }

  public function updatePage(Request $request, $id)
{
    // 1. Pehle page find karein
    $page = \App\Models\Page::findOrFail($id);

    // 2. Validation Rules
    $rules = [
        'p_title'     => 'required|string|max:255',
        // unique rule mein ID ignore karna zaroori hai
        'slug'        => 'required|string|unique:pages,slug,' . $id, 
        'template_id' => 'required|exists:page_templates,id',
        'post_status' => 'required|integer',
        'bcat'        => 'nullable|array',
    ];

    $messages = [
        'p_title.required'     => 'The page title is required.',
        'slug.required'        => 'The slug is missing.',
        'slug.unique'          => 'This slug is already in use by another page.',
        'template_id.required' => 'Selecting a template is mandatory.',
        'post_status.required' => 'Please select a post status.',
        'bcat.array'           => 'The format of the selected slides is invalid.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'errors'  => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // 3. Data Update Karein
        $page->title            = $request->p_title;
        $page->slug             = $request->slug;
        $page->content          = $request->p_content;
        $page->meta_title       = $request->mtitle;
        $page->meta_description = $request->meta_desc;
        $page->template_id      = $request->template_id;
        $page->status_id        = $request->post_status;
        $page->image_id         = $request->profile_image_id;

        // slider_id handle karein (Model casting 'array' hai toh direct assign karein)
        if ($request->has('bcat') && is_array($request->bcat)) {
            $page->slider_id = json_encode($request->bcat); 
        } else {
            $page->slider_id = null;
        }

        $page->update(); // Ya save() dono kaam karenge

        DB::commit();

        return response()->json([
            'success' => true, 
            'message' => 'Page updated successfully!'
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'success' => false, 
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}
public function deletePage($id)
{
    try {
        $page = \App\Models\Page::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}

}
