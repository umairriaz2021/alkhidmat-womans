<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
     
    public function index()
    {
          $menus = Menu::with(['submenu'])->get()->toArray();
           return view('admin.dashboard.menus.all-menus',compact('menus')); 
    }

      public function createMenu(Request $request){
         $statuses = Status::get()->toArray();
         $menus = Menu::get()->toArray();
         $pages = Page::get()->select('id','title','slug')->toArray();
         
         if($request->isMethod('POST'))
            {
                $rules = [
                'ln'          => 'required|string|max:255',
                'lu'          => 'nullable|string', 
                'parent_id'   => 'nullable|exists:menus,id',
                'post_status' => 'required|exists:status,id',
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
            // 2. Data Saving Logic
            $menu = new Menu();
            $menu->title       = $request->ln;
            $menu->url         = $request->lu;
            $menu->parent_id   = $request->parent_id ?: null;
            $menu->status_id   = $request->post_status;
            $menu->mega_menu   = $request->has('mg_menu') ? 1 : 0;
           

            $menu->save();

            return redirect()->back()->with('success', 'Menu created successfully');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Database error: ' . $e->getMessage()])->withInput();
        }
            }
            return view('admin.dashboard.menus.create-menus',compact('statuses','menus','pages')); 
           }

      public function editMenu($id)
      {
           $menu_data = Menu::find($id)->toArray();
           $statuses = Status::get()->toArray();
           $menus = Menu::get()->toArray();
           $pages = Page::get()->select('id','title','slug')->toArray();
           return view('admin.dashboard.menus.create-menus',compact('statuses','menu_data','menus','pages'));

      }

      public function updateMenu(Request $request, $id)
{
   
        
        // 1. Validation Rules
        $rules = [
            'ln'          => 'required|string|max:255',
            'lu'          => 'nullable|string', 
            'parent_id'   => 'nullable|exists:menus,id',
            // Note: Table ka naam 'statuses' hota hai aksar, check karlein agar 'status' hai ya 'statuses'
            'post_status' => 'required|exists:status,id', 
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
            
            // Checkbox logic for update
            $menu->mega_menu   = $request->has('mg_menu') ? 1 : 0;

            $menu->save(); // Ya $menu->update()

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
}
