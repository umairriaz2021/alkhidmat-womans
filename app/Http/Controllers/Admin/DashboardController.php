<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        

          return view('admin.dashboard.index');
    }

    public function AllUsers()
    {
        $users = User::with(['profileImage','roles'])->get()->map(function($user){
              return [
                  'id' => $user->id,
                  'name' => $user->name,
                  'email' => $user->email,
                  'image_path' => $user->profileImage->file_path,
                  'img_id'  => $user->profileImage->id,
                  'role_id' => $user->roles->pluck('id')->first(),
                  'role_name' => $user->roles->pluck('name')->first()   
              ];
        });
        $users = json_decode(json_encode($users),true);
        // echo "<pre>"; print_r($users);die;
          return view('admin.dashboard.users.all_users',compact('users'));
    }

    public function editUser($id)
    {
          $userObject = User::with(['roles','profileImage'])->find($id);
        //   $userObject = json_decode(json_encode($userObject),true);
          
if ($userObject) {
    $formattedUser = [
        'id'        => $userObject->id,
        'name'      => $userObject->name,
        'email'     => $userObject->email,
        'password'  => $userObject->password,
        'image_path' => $userObject->profileImage->file_path,
        'image_id'  => $userObject->profileImage->id,
        // Yahan hum sirf ID aur Name nikal rahe hain
        'role_id'   => $userObject->roles->first()?->id,
        'role_name' => $userObject->roles->first()?->name,
    ];
       
    }
    
    $roles = Role::get()->map(function($role){
           return [
               'id' => $role->id,
               'name' => $role->name
           ];
    });
    $roles = json_decode(json_encode($roles),true);
    //echo "<pre>"; print_r($roles);die;    
      return view('admin.dashboard.users.edit',['user'=>$formattedUser,'roles'=>$roles]);
    }

     public function updateUser(Request $request, $id)
{
     
    $request->validate([
        'n'                => 'required|string|max:255',
        'e'                => 'required|email|unique:users,email,' . $id,
        'role_id'          => 'nullable', // Validation simple rakhein kyunki hum ID ya Name bhej sakte hain
        'profile_image_id' => 'nullable|exists:media,id',
        'password'         => 'nullable|min:6',
    ]);

    $user = User::findOrFail($id);
    
    // Basic Info Update
    $user->update([
        'name'  => $request->n,
        'email' => $request->e,
    ]);

    // 1. Spatie Role Update (Conditional)
    if ($request->filled('role_id')) {
        // Hum role ID se role dhoond kar sync karenge
        $role = Role::find($request->role_id);
        if ($role) {
            $user->syncRoles($role->name); // Purane roles khatam karke naya set kar dega
        }
    }

    // 2. Password Update
    if ($request->filled('password')) {
        $user->update(['password' => Hash::make($request->password)]);
    }

    // 3. Profile Image Update
    if ($request->filled('profile_image_id')) {
        
        $user->update(['image_id' => $request->profile_image_id]);
    }

    return redirect()->back()->with('success', 'User data has been updated!');
}
  public function createUser(Request $request)
  {
    $roles = Role::get()->map(function($role){
           return [
               'id' => $role->id,
               'name' => $role->name
           ];
    });
    $roles = json_decode(json_encode($roles),true);

       if ($request->isMethod('POST')) {
        // Validation
        $request->validate([
            'n'                => 'required|string|max:255',
            'e'                => 'required|email|unique:users,email',
            'role_id'          => 'required|exists:roles,id',
            'profile_image_id' => 'nullable|exists:media,id',
            'password'         => 'required|min:6',
        ], [
            // Custom English Messages
            'n.required' => 'The user name field is mandatory.',
            'n.max'      => 'The name may not be greater than 255 characters.',
            'e.required' => 'A valid email address is required.',
            'e.email'    => 'Please enter a valid email format (e.g., user@example.com).',
            'e.unique'   => 'This email address is already registered in our system.',
            'role_id.required' => 'Please assign a role to this user.',
            'role_id.exists'   => 'The selected role is invalid.',
            'password.required' => 'A password is required for new accounts.',
            'password.min'      => 'The password must be at least 6 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'profile_image_id.exists' => 'The selected image does not exist in our library.',
        ]);

        try {
            // User Create karein
            $user = User::create([
                'name'     => $request->n,
                'email'    => $request->e,
                'password' => Hash::make($request->password),
                'image_id' => $request->profile_image_id,
            ]);

            // Role Assign karein (Spatie)
            $role = Role::findById($request->role_id);
            $user->assignRole($role->name);

            return redirect()->route('admin.users.index')->with('success', 'User account created successfully!');

        } catch (\Exception $e) {
            // Log the error and return back
            return redirect()->back()
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

        return view('admin.dashboard.users.create_users',compact('roles'));
  }

  public function deleteUser($id)
  {
       $deleteUser = User::where('id',$id)->delete();
       return response()->json(['success'=>true,'message'=>'User deleted successfully']);
  }
}
