<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Status;

use Illuminate\Http\Request;

class SliderController extends Controller
{
     public function index()
    {
         $statuses = Status::get()->toArray();
         $sliders = Slider::with(['profileImage'])->get()->toArray();
         
         return view('admin.dashboard.sliders.index',compact('statuses','sliders'));
    }

    public function createSlider(Request $request)
    {
           $statuses = Status::get()->toArray();
           if($request->isMethod('POST'))
            {
             
               $messages = [
                    'main_heading.required'     => 'The main heading field is mandatory.',
                    'tagline.max'               => 'The tagline may not be greater than 255 characters.',
                    'content.string'            => 'The content must be a valid text string.',
                    'cta_text.max'              => 'The button text (CTA) is too long.',
                    'cta_url.url'               => 'Please enter a valid URL (e.g., https://example.com).',
                    'profile_image_id.required' => 'Please select a slider image from the media manager.',
                    'post_status.required'      => 'Please select a status for this slider.',
                    'post_status.exists'        => 'The selected status is invalid.',
                    'donation_projects.*.string' => 'Each donation project must be a valid string.',
                    'donation_types.*.string'    => 'Each donation type must be a valid string.',
               ];
               $validatedData = $request->validate([
               'tagline'          => 'nullable|string|max:255',
               'content'          => 'nullable|string',
               'cta_text'         => 'nullable|string|max:255',
               'cta_url'          => 'nullable|url|max:255',
               'profile_image_id' => 'required|string', 
               'post_status'      => 'required|exists:status,id',
               'main_heading'     => 'nullable|string|max:255',
               // Validation for Dynamic Arrays
                'donation_projects'   => 'nullable|array',
                'donation_projects.*' => 'nullable|string|max:255',
                'donation_types'      => 'nullable|array',
                'donation_types.*'    => 'nullable|string|max:255', 
          ], $messages);

          try {
        $slider = new \App\Models\Slider();
        
        $slider->tagline      = $request->tagline;
        $slider->content      = $request->content;
        $slider->cta_text     = $request->cta_text;
        $slider->cta_url      = $request->cta_url;
        $slider->image_id     = $request->profile_image_id; // Mapping
        $slider->status_id    = $request->post_status;      // Mapping
        
        
        $slider->main_heading = $request->main_heading ?? $request->tagline ?? 'Default Heading';
        
        $slider->donation_projects = $request->donation_projects ? array_filter($request->donation_projects) : null;
        $slider->donation_types    = $request->donation_types ? array_filter($request->donation_types) : null;
       //$slider->donation_projects = json_encode(array_values(array_filter($request->donation_projects)));
//$slider->donation_types    = json_encode(array_values(array_filter($request->donation_types)));
        $slider->save();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully!');
        
    } catch (\Exception $e) {
       // dd($e->getSql(), $e->getBindings(), $e->getMessage());
        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }

            }

            return view('admin.dashboard.sliders.create',compact('statuses'));
    }

    public function edit($id)
    {
          $slider = Slider::with(['profileImage'])->find($id)->toArray();
          $statuses = Status::get()->toArray();
          
          
          return view('admin.dashboard.sliders.create',compact('slider','statuses'));
       
    }

    public function update(Request $request, $id)
    {
           $statuses = Status::get()->toArray();
           $slider = Slider::findOrFail($id);
           
           $messages = [
            'main_heading.required'     => 'The main heading field is mandatory.',
            'tagline.max'               => 'The tagline may not be greater than 255 characters.',
            'cta_url.url'               => 'Please enter a valid URL.',
            'profile_image_id.required' => 'Please select a slider image.',
            'post_status.required'      => 'Please select a status.',
        ];
        $request->validate([
            'tagline'          => 'nullable|string|max:255',
            'content'          => 'nullable|string',
            'cta_text'         => 'nullable|string|max:255',
            'cta_url'          => 'nullable|url|max:255',
            'profile_image_id' => 'nullable|string', 
            'post_status'      => 'required|exists:status,id', // 'status' ko 'statuses' table name se fix kiya
            'main_heading'     => 'nullable|string|max:255', 
        ], $messages);

        try {
            // Data Update
            $slider->tagline      = $request->tagline;
            $slider->content      = $request->content;
            $slider->cta_text     = $request->cta_text;
            $slider->cta_url      = $request->cta_url;
            $slider->image_id     = $request->profile_image_id; 
            $slider->status_id    = $request->post_status;      
            
            $slider->main_heading = $request->main_heading;
            $slider->page_id      = $request->page_id ?? null;

            $slider->save();

            return redirect()->route('admin.slider')->with('success', 'Slider updated successfully!');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function deleteSlider($id)
{
    try {
        $slider = \App\Models\Slider::findOrFail($id);
        
        // Agar aap slider ki image folder se bhi delete karna chahte hain:
        /*
        if ($slider->image_path && file_exists(public_path($slider->image_path))) {
            unlink(public_path($slider->image_path));
        }
        */

        $slider->delete();

        return response()->json([
            'success' => true,
            'message' => 'Slider deleted successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
}
