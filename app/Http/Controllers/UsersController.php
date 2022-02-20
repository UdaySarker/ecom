<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users=User::orderBy('id','ASC')->paginate(10);
        return view('backend.users.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
        [
            'name'=>'string|required|max:30|min:4',
            'email'=>'string|required|unique:users',
            'password'=>'required|min:6|alpha_num',
            'role'=>'required|in:admin,user',
            'status'=>'required|in:active,inactive',
            'profile_photo'=>'nullable|image|mimes:jpg,png,jpeg',
        ]);
        // dd($request->all());
        $data=$request->all();
        $data['password']=Hash::make($request->password);
        //dd($data);
        $status=User::create($data);
        if($status){
            request()->session()->flash('success','Successfully added user');
        }
        else{
            request()->session()->flash('error','Error occurred while adding user');
        }
        return redirect()->route('users.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::findOrFail($id);
        return view('backend.users.edit')->with('user',$user);
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
        $user=User::findOrFail($id);
        $this->validate($request,
        [
            'name'=>'string|required|max:30|min:4',
            'email'=>'string|required',
            'password'=>'required|min:6|alpha_num',
            'role'=>'required|in:admin,user',
            'status'=>'required|in:active,inactive',
            'profile_photo' =>'nullable|image|mimes:jpg,png,jpeg',
        ]);
        // dd($request->all());
        $data=$request->all();
        // dd($data);
        if(empty($request->file('profile_photo')))
        {
            $data['photo']=$user->photo;
        }else{
            $image_path=$request->file('profile_photo')->storeAs('users_image',$request->file('profile_photo')->getClientOriginalName());
            $data['photo']=$image_path;
            unset($data['profile_photo']);
        }
        $data['password']=Hash::make($request->password);
        $status=$user->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated');
        }
        else{
            request()->session()->flash('error','Error occured while updating');
        }
        return redirect()->route('users.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete=User::findorFail($id);
        $status=$delete->delete();
        if($status){
            request()->session()->flash('success','User Successfully deleted');
        }
        else{
            request()->session()->flash('error','There is an error while deleting users');
        }
        return redirect()->route('users.index');
    }
    public function incomeFromBookSale()
    {
        $userPurchases=Order::where('user_id',Auth()->user()->id)->get();

        $expenseData=DB::table('user_wallet')->where('book_owner_id','=',Auth()->user()->id)->where('ct_amt','>',0)->get();
        return view('user.userincome')
        ->with('data',$expenseData)
        ->with('userpurchases',$userPurchases);
    }
}
