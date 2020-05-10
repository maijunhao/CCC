<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\StoreUsers;
use App\Models\Users;
use App\Models\UsersInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * 用户列表页
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //接收搜索参数
        $search_users = $request->input('search_uname','');
        $search_email = $request->input('search_email','');
        //处理搜索语句
        $users = Users::where('uname','like','%'.$search_users.'%')->where('uname','like','%'.$search_email.'%')->paginate(5);
        return view('admin.users.index',['users'=>$users,'params'=>$request->all()]);
    }

    /**
     * 用户添加页
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.users.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsers $request)
    {
//        验证数据
//        $this->validate($request,[
//            'uname' => 'required|regex:/^[a-zA-Z]{1}[\w]{5,17}$/',
//            'upass' => 'required|regex:/^[\w]{6,18}$/',
//            'repass' => 'required|same:upass',
//            'email' => 'required|email',
//            'phone' => 'required|',
//            'profile' => 'required',
//        ],[
//            'uname.required'=>'用户名必填',
//            'uname.regex'=>'用户名格式错误',
//            'upass.required'=>'密码必填',
//            'upass.regex'=>'密码格式错误',
//            'repass.required'=>'确认密码必填',
//            'repass.regex'=>'两次密码不一致',
//            'email.required'=>'邮箱必填',
//            'email.email'=>'邮箱格式错误',
//            'phone.required'=>'手机号必填',
//            'phone.regex'=>'手机号格式错误',
//            'profile.required'=>'头像必填',
//        ]);


        //开启事务
        DB::beginTransaction();
        //上传头像，等待事务确定
        if($request->hasFile('profile')){
            $file_path = $request->file('profile')->store(date('Ymd'));
        }else{
            $file_path = '';
        }
        //插入数据进Users表，等待事务确定
        $data = $request->all();
        $user = new Users;
        $user->uname = $data['uname'];
        $user->upass = Hash::make($data['upass']);
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $res1 = $user->save();

        if($res1){
            $uid = $user->id;
        }
        //压入头像，等待事务确定
        $userinfo = new UsersInfo;
        $userinfo->uid = $uid;
        $userinfo->profile = $file_path;
        $res2 = $userinfo->save();
        //事务回滚，确认
        if($res1 && $res2){
            DB::commit();
            return redirect('admin/users')->with('success','添加成功');
        }else{
            DB::rollback();
            return back()->with('error','添加失败');
        }
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
        //
        $user = Users::find($id);
        return view('admin.users.edit',['user'=>$user]);

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
        DB::beginTransaction();

        //获取头像
        if($request->hasFile('profile')){
            $file_path = $request->file('profile')->store(date('Ymd'));
        }else{
            $file_path = $request->input('old_profile');
        }

        $user = Users::find($id);
        $user->email = $request->input('email','');
        $user->phone = $request->input('phone','');
        $res1 = $user->save();
        $userinfo = UsersInfo::where('uid',$id)->first();
        $userinfo->profile = $file_path;
        $res2 = $userinfo->save();
        //事务回滚，确认
        if($res1 && $res2){
            DB::commit();
            return redirect('admin/users')->with('success','删除成功');
        }else {
            DB::rollback();
            return back()->with('error', '删除失败');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //开启事务
        DB::beginTransaction();
        //接收参数，处理参数
        $pro = UsersInfo::where('uid',$id)->value('profile');
        $res1 = Users::destroy($id);
        $res2 = UsersInfo::where('uid',$id)->delete();

        //删除用户头像文件
        //use Illuminate\Support\Facades\Storage;
        Storage::delete($pro);


        //事务回滚，确认
        if($res1 && $res2){
            DB::commit();
            return redirect('admin/users')->with('success','删除成功');
        }else{
            DB::rollback();
            return back()->with('error','删除失败');
        }
    }
}
