<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use DB;
use Hash;
use Session;
use Auth;



class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = array();
		$users = User:: all();//orderBy('id','DESC')->paginate(5);
        /* return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5); */
			//echo '<pre>';print_r($users);exit;
		return view('body.users.index')
					->withUsers($users)
					->withData($data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::lists('display_name','id'); //echo '<pre>';print_r($roles);exit;
		$depts = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		$loc = DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
        return view('body.users.add',compact('roles','depts'),compact('loc','loc'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input); 
        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::lists('display_name','id');
        $userRole = $user->roles->lists('id','id')->toArray();
		$depts = DB::table('department')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
		$loc = DB::table('location')->where('status',1)->where('deleted_at','0000-00-00 00:00:00')->select('id','name')->get();
        //return view('users.edit',compact('user','roles','userRole'));
		
		$roles = Role::lists('display_name','id'); //echo '<pre>';print_r($roles);exit;
        return view('body.users.edit',compact('roles','user','userRole','depts','loc'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }


        $user = User::find($id);
        $user->update($input);
        DB::table('role_user')->where('user_id',$id)->delete();

        
        foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }


        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
	
	public function deluser($id)
    {
        User::find($id)->delete();
		Session::flash('message', 'User deleted successfully.');
        return redirect()->route('users.index');
                        //->with('success','User deleted successfully');
    }
	
	public function changePassword() {
		
        return view('body.users.password');
	}
	
	public function updatePassword(Request $request) {
		
	  $data = User::find(Auth::User()->id);
	  if(Hash::check($request->password, $data->password)) {
		  
		  if(!empty($request->password)) { 
			   $input['password'] = Hash::make($request->new_password);
			   $data->update($input);
			   Session::flash('message', 'Password changed successfully.');
			   return redirect('users/reset/password');
		  } else {
			  Session::flash('error', 'Invalid password entry!');
			  return redirect('users/reset/password');
		  }
	  } else {
		  Session::flash('error', 'Invalid current password!');
		  return redirect('users/reset/password');
	  }
	  
	
	   
	}
}