<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PageTemplate;
use App\Models\Status;
class TemplateController extends Controller
{
    public function index()
    {
         $templates = PageTemplate::with(['statuses'])->get()->toArray();
         
         return view('admin.dashboard.templates.index',compact('templates'));
    }

    public function createPageTemplates(Request $request)
    {
          $statuses = Status::get()->toArray();
           if($request->isMethod('POST'))
            {
                $request->validate([
                    'temp_name'   => 'required|string|max:255',
                    'temp_status' => 'required|exists:status,id'
                ]);
                try {
                $template = new PageTemplate();
                $template->display_name = ucfirst($request->temp_name);
                $template->template_name = str_replace(' ','_',strtolower($request->temp_name));
                $template->status_id = $request->temp_status;
                $save = $template->save();
                return redirect()->back()->with('success', 'Template created successfully!');
                }
                catch(\Exception $e)
                {
                      return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
                }
            
                }
            return view('admin.dashboard.templates.create',compact('statuses'));
    }

    public function editTemplate($id)
    {
          $template_data = PageTemplate::with(['statuses'])->find($id)->toArray();
          $statuses = Status::get()->toArray();
          return view('admin.dashboard.templates.create',compact('statuses','template_data'));
    }

    public function updateTemplate(Request $request,$id)
    {

    }
}
