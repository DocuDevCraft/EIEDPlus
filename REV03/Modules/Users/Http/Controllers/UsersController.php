<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\Users\Entities\Users;
use Modules\Users\Http\Requests\CreateUserRequest;
use Modules\Users\Http\Requests\UpdateUsersRequest;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $Users = Users::where('first_name', 'like', "%{$request->search}%")
            ->orWhere('last_name', 'like', "%{$request->search}%")
            ->orWhere('email', 'like', "%{$request->search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('users::index', compact('Users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('users::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(CreateUserRequest $request)
    {
        $UserData = $request->all();


        if ($request->file('avatar')) {
            $UserData['avatar'] = FileLibraryController::upload($request->file('avatar'), 'image', 'users/avatar', 'users', array([35, 35, 'fit'], [46, 46, 'fit'], [63, 63, 'fit'], [70, 70, 'fit'], [92, 92, 'fit'], [126, 126, 'fit'], [246, 246, 'fit'], [600, 600, 'fit']));
        }
        function random_password($length = 8)
        {
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
            $password = substr(str_shuffle($chars), 0, $length);
            return $password;
        }

        $Pass = random_password();

        $UserData['password'] = Hash::make($Pass);

        if ($User = Users::create($UserData)) {
            $RoleName = \App\Http\Controllers\HomeController::RoleTranslation($request->role);
            $LoginUrl = env('APP_PANEL_URL') . '/login';

            SmsHandlerController::Send(["$request->phone"], "$request->first_name $request->last_name عزیز به سامانه برونسپاری بسته های کاری شرکت EIED خوش آمدید.
حساب کاربری شما با عنوان $RoleName ایجاد شده است.
لطفا برای ورود به حساب کاربری از اطلاعات زیر استفاده نمایید:
آدرس ورود: $LoginUrl
نام کاربری: $request->email
کلمه عبور: $Pass    ");
            return redirect('dashboard/users')->with('notification', [
                'class' => 'success',
                'message' => 'حساب کاربری جدید با موفقیت ایجاد شد'
            ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'alert',
                'message' => 'حساب کاربری جدید با موفقیت ایجاد نشد'
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('users::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if ($id == auth()->user()->id || Gate::allows('isAdmin')) {
            $Users = Users::find($id);

            $Data = [
                'Users',
            ];
            return view('users::edit', compact($Data));
        } else {
            return redirect('/dashboard/no-permissions');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(UpdateUsersRequest $request, $id)
    {
        if ($id == auth()->user()->id || Gate::allows('isAdmin')) {
            $users = Users::find($id);
            $UserData = $request->all();

            if ($request->file('avatar')) {
                $UserData['avatar'] = FileLibraryController::upload($request->file('avatar'), 'image', 'users/avatar', 'users', array([35, 35, 'fit'], [46, 46, 'fit'], [63, 63, 'fit'], [70, 70, 'fit'], [92, 92, 'fit'], [126, 126, 'fit'], [246, 246, 'fit'], [600, 600, 'fit']));
            }


            if ($request->role == 'super_admin') {
                $UserData['role'] = 'user';
            } else {
                if (auth()->id() === $users->id) {
                    $UserData['role'] = $users->role;
                } else {
                    $UserData['role'] = $request->role;
                }
            }


            if (!empty($request->password_change)) {
                $UserData['password'] = Hash::make($request->password_change);
            }

            if ($users->update($UserData)) {
                return back()->with('notification', ['class' => 'success', 'message' => 'کاربر مورد نظر با موفقیت ویرایش شد.']);
            } else {
                return redirect()->back()->with('notification', [
                    'status' => 'danger',
                    'message' => 'کاربر مورد نظر ویرایش نشد!',
                ]);
            }
        } else {
            return redirect('/dashboard/no-permissions');
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        foreach ($request->delete_item as $key => $item) {
            /* Resume Delete */
            Users::where('id', $key)->delete();
        }

        return redirect('/dashboard/users')->with('notification', [
            'class' => 'success',
            'message' => 'کاربران مورد نظر حذف شد'
        ]);
    }
}
