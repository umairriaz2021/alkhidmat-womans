<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settingUpdate(Request $request)
    {
          if($request->isMethod('post'))
            {

            }
          return view('admin.dashboard.settings.index');
    }

}
