<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Status;
use App\Models\Slider;
use App\Models\Menu;
use App\Models\PageTemplate;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Models\Area;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use App\Models\Setting;
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
    $categories = Category::select('id','name','slug')->get()->toArray();

    if ($request->isMethod('post')) {
        $rules = [
            'p_title'     => 'required|string|max:255',
            'slug'        => 'required|string|unique:pages,slug',
            'template_id' => 'required|exists:page_templates,id',
            'post_status' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id', // Optional Category Validation
            'bcat'        => 'nullable|array',
        ];

        $messages = [
            'p_title.required'     => 'The page title is required.',
            'slug.required'        => 'The slug could not be generated. Please check the title.',
            'slug.unique'          => 'This slug is already in use for another page.',
            'template_id.required' => 'Selecting a template is mandatory.',
            'post_status.required' => 'Please select a post status.',
            'category_id.exists'   => 'The selected category is invalid.',
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

            $page = new \App\Models\Page();
            
            $page->user_id          = auth()->id() ?? 1;
            $page->title            = $request->p_title;
            $page->slug             = $request->slug;
            $page->content          = $request->p_content;
            $page->meta_title       = $request->mtitle;
            $page->meta_description = $request->meta_desc;
            $page->template_id      = $request->template_id;
            $page->status_id        = $request->post_status;
            $page->image_id         = $request->profile_image_id;
            
            // Category Logic (Optional Check)
            $page->category_id      = $request->filled('category_id') ? $request->category_id : null;

            // Slider Logic
            if ($request->has('bcat') && is_array($request->bcat)) {
                $page->slider_id = json_encode($request->bcat);
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

    return view('admin.dashboard.pages.create', compact('statuses', 'sliders', 'templates', 'categories'));
}

  public function editPage($id)
  {
      $page = Page::with(['profileImage','template'])->find($id)->toArray();
      //echo "<pre>"; print_r($page);die;
      $sliderIds = json_decode($page['slider_id'],true);
      $statuses = Status::get()->toArray();
      $categories = Category::select('id','name','slug')->get()->toArray();
      $sliders = Slider::get()->toArray();
      $templates = PageTemplate::get()->toArray();

    //   $sliders = Slider::whereIn('id', $sliderIds)
    //         ->orderByRaw("FIELD(id, ".implode(',', $sliderIds).")")
    //         ->select('id','main_heading')
    //         ->get()->toArray();
      return view('admin.dashboard.pages.create',compact('page','statuses','sliders','templates','categories'));
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

 public function show($slug = 'home') // Default slug 'home' rakha hai
{ 


$settingsData = Setting::with(['siteLogo', 'footerLogo'])->first();
$settings = $settingsData ? $settingsData->toArray() : [];
$menus = Menu::with(['submenu','parent','status'])->orderBy('order', 'asc')->get()->toArray();

$paymentMethod = PaymentMethod::with(['status'])->get()->toArray();
$page = Page::with(['profileImage','template'])
                ->where('slug', $slug)
                ->firstOrFail();
                
$sliderIds = json_decode($page->slider_id) ?? []; 
$sliders = Slider::with(['profileImage'])->whereIn('id', $sliderIds)->where('status_id',2)->get()->toArray();
    $dynamicData = ['blogs'];
    $dynamicDataArr = [
        'page' => $page,
        'sliders' => $sliders, // Alag se pass karein
        'template_name' => $page->template->template_name,
        'settings' => $settings,
        'menus' =>  $menus,
        'data' => (in_array($slug,$dynamicData)) ? $this->getData($slug) : []
    ];
    if($slug === 'donation-summary')
    {
          $dynamicDataArr['payment_methods'] = $paymentMethod;
    }
    return Inertia::render('DynamicPage',$dynamicDataArr );
}


public function getData($slug){
     $data = []; 
     if($slug === 'blogs')
      {
        $data = Category::with(['posts.profileImage'])->where('slug',$slug)->first()->toArray();
      }
      return $data; 
}
public function blogsDisplay($cat,$slug)
{
    
   
    $category = Category::where('slug',$cat)->first();
    
    

    $settingsData = Setting::with(['siteLogo', 'footerLogo'])->first();
    $settings = $settingsData ? $settingsData->toArray() : [];
    $menus = Menu::with(['submenu','parent','status'])->orderBy('order', 'asc')->get()->toArray();
  
    
    if($category->slug === 'area-of-work')
    {
        $slug = '/area-of-work/'.$slug;
        //echo "<pre>"; print_r($slug);die;
        $area = Area::with(['category','profileImage'])->where(['category_id'=>$category->id,'slug'=>$slug])
                ->firstOrFail()->toArray();
               //    echo "<pre>"; print_r($area);die;
     return Inertia::render('DynamicPage', [
        'page' => $area,
        'sliders' => [], // Alag se pass karein
        'template_name' => 'single_area_of_work',
        'settings' => $settings,
        'menus' =>  $menus,
        'data' => ['categories'=>Category::get()->toArray(),'posts'=>Area::with('profileImage')->latest()->paginate(5)]
        
    ]);
    }
    if($category->slug === 'programs')
    {
        
        //echo "<pre>"; print_r($slug);die;
        $area = Page::with(['category','profileImage'])->where(['category_id'=>$category->id,'slug'=>$slug])
                ->firstOrFail()->toArray();
               //    echo "<pre>"; print_r($area);die;
     return Inertia::render('DynamicPage', [
        'page' => $area,
        'sliders' => [], // Alag se pass karein
        'template_name' => 'programs_pages',
        'settings' => $settings,
        'menus' =>  $menus,
        'data' => ['categories'=>Category::get()->toArray(),'posts'=>Page::with('profileImage')->latest()->paginate(5)]
        
    ]);
    }

   else{
        $post = Post::with(['category','profileImage'])->where(['category_id'=>$category->id,'slug'=>$slug])
                ->firstOrFail()->toArray();
     return Inertia::render('DynamicPage', [
        'page' => $post,
        'sliders' => [], // Alag se pass karein
        'template_name' => 'single_blog_page',
        'settings' => $settings,
        'menus' =>  $menus,
        'data' => ['categories'=>Category::get()->toArray(),'posts'=>Post::with('profileImage')->latest()->paginate(5)]
        
    ]);
   }

      
}

}


