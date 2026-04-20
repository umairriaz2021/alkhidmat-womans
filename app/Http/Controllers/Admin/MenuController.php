<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Support\Facades\Validator;
use App\Models\MegaMenu;

class MenuController extends Controller
{
     
    public function index()
    {
         $menus = Menu::with(['submenu','parent'])->get()->toArray();

          return view('admin.dashboard.menus.all-menus',compact('menus')); 
    }

    public function createMenu(Request $request)
{
    $statuses = Status::all(); // toArray() ki zaroorat nahi agar aap blade mein foreach use kar rahe hain
    $menus = Menu::all();
    $megaMenu = MegaMenu::all();
    $pages = Page::select('id', 'title', 'slug')->get();

    if ($request->isMethod('POST')) {
        $rules = [
            'ln'            => 'required|string|max:255',
            'lu'            => 'nullable|string',
            'parent_id'     => 'nullable|exists:menus,id',
            'post_status'   => 'required|exists:status,id',
            'mega_menus_id'  => 'nullable|array', // Checkbox array validate karein
            'mega_menus_id.*'=> 'exists:mega_menus,id', // Array ki har ID check karein
        ];

        $messages = [
            'ln.required' => 'Link Name is required',
            'lu.url'      => 'Link URL format is not correct',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $menu = new Menu();
            $menu->title       = $request->ln;
            $menu->url         = $request->lu;
            $menu->parent_id   = $request->parent_id ?: null;
            $menu->status_id   = $request->post_status;
            
            // Multiple IDs save karna (Model casting isay JSON bana degi)
            $menu->mega_menus_id = $request->mega_menus_id; 

            $menu->save();

            return redirect()->back()->with('success', 'Menu created successfully');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Database error: ' . $e->getMessage()])->withInput();
        }
    }

    return view('admin.dashboard.menus.create-menus', compact('statuses', 'menus', 'pages', 'megaMenu'));
}

      public function editMenu($id)
      {
           $menu_data = Menu::with(['submenu','parent'])->find($id)->toArray();
            //echo "<pre>"; print_r($menu_data);die;           
           $statuses = Status::get()->toArray();
           $menus = Menu::get()->toArray();
           $megaMenu = MegaMenu::all()->toArray();
           $pages = Page::get()->select('id','title','slug')->toArray();
           return view('admin.dashboard.menus.create-menus',compact('statuses','menu_data','menus','megaMenu','pages'));

      }

      public function updateMenu(Request $request, $id)
{
    // 1. Validation Rules
    $rules = [
        'ln'               => 'required|string|max:255',
        'lu'               => 'nullable|string', 
        'parent_id'        => 'nullable|exists:menus,id',
        'post_status'      => 'required|exists:status,id', // Check karein table name 'status' hai ya 'statuses'
        'mega_menus_id'    => 'nullable|array',           // Array validation
        'mega_menus_id.*'  => 'exists:mega_menus,id',      // Array ki har ID check karein
    ];

    $messages = [
        'ln.required' => 'Link Name is required',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        // 2. Find existing record
        $menu = Menu::findOrFail($id);

        // 3. Data Updating Logic
        $menu->title       = $request->ln;
        $menu->url         = $request->lu;
        $menu->parent_id   = $request->parent_id ?: null;
        $menu->status_id   = $request->post_status;
        
        // 4. Mega Menu Logic (Array handle karna)
        // Agar parent_id empty hai to mega_menus save karein, warna null (optional safety logic)
        if ($request->filled('parent_id')) {
            $menu->mega_menus_id = null; 
        } else {
            $menu->mega_menus_id = $request->mega_menus_id; 
        }

        $menu->save(); 

        return redirect()->back()->with('success', 'Menu updated successfully');

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Database error: ' . $e->getMessage()])->withInput();
    }
}

public function deleteMenu($id)
{
    try {
        $menu = \App\Models\Menu::findOrFail($id);
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}
public function showMegaMenus()
{
     $megaMenu = MegaMenu::get()->toArray();
    
     return view('admin.dashboard.menus.megamenus.all',compact('megaMenu'));
}

public function createMegaMenus(Request $request)
{
     $menus = Menu::with('parent','submenu')->get()->toArray();
     $statuses = Status::get()->toArray(); 

    if ($request->isMethod('post')) {
        // 1. Validation
        $rules = [
            'group_name'  => 'required|string|max:255',
            'menu_id'     => 'required|array', // IDs ka array hona zaroori hai
            'menu_id.*'   => 'exists:menus,id',
            'post_status' => 'required|exists:status,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // 2. Data Saving Logic
            $megaMenu = new MegaMenu();
            $megaMenu->group_name = $request->group_name;
            $megaMenu->status_id  = $request->post_status;
         
            $megaMenu->links = $request->menu_id; 

            $megaMenu->save();

            return redirect()->back()->with('success', 'Mega Menu created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])->withInput();
        }
    }

    return view('admin.dashboard.menus.megamenus.create', compact('menus', 'statuses'));    
}

public function editMegaMenus($id)
{
    $megaMenuData = MegaMenu::findOrFail($id); // Table: mega_menus
    $menus = Menu::all();
    $statuses = Status::all();

    // Same blade file 'create.blade.php' use ho rahi hai
    return view('admin.dashboard.menus.megamenus.create', compact('megaMenuData', 'menus', 'statuses'));
}
public function updateMegaMenus(Request $request, $id)
{
    $request->validate([
        'group_name'  => 'required|string|max:255',
        'menu_id'     => 'required|array',
        'post_status' => 'required|exists:status,id',
    ]);

    try {
        $megaMenu = MegaMenu::findOrFail($id);
        $megaMenu->group_name = $request->group_name;
        $megaMenu->status_id  = $request->post_status;
        $megaMenu->links      = $request->menu_id; // Model casting array ko JSON bana degi
        $megaMenu->save();

        return redirect()->back()->with('success', 'Mega Menu updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}
public function deleteMegaMenu($id)
{
    try {
        $menu = MegaMenu::findOrFail($id);
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mega Menu deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong: ' . $e->getMessage()
        ], 500);
    }
}
}