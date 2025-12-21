<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = FileLibraryController::upload(
                $request->file('avatar'),
                'image',
                'users/avatar',
                'users',
                [
                    [35, 35, 'fit'], [46, 46, 'fit'], [63, 63, 'fit'],
                    [70, 70, 'fit'], [92, 92, 'fit'], [126, 126, 'fit'],
                    [246, 246, 'fit'], [600, 600, 'fit'],
                ]
            );
        }

        $plainPassword = Str::random(8);
        $data['password'] = Hash::make($plainPassword);

        $user = Users::create($data);

        $roleName = HomeController::RoleTranslation($request->role);
        $loginUrl = config('app.panel_url') . '/login';

        SmsHandlerController::Send(
            [$request->phone],
            "{$request->first_name} {$request->last_name} عزیز،
حساب کاربری شما با نقش {$roleName} ایجاد شد.
نام کاربری: {$request->email}
کلمه عبور: {$plainPassword}
آدرس ورود: {$loginUrl}"
        );

        return redirect('dashboard/users')->with('notification', [
            'class' => 'success',
            'message' => 'حساب کاربری با موفقیت ایجاد شد'
        ]);
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
        abort_unless($id == auth()->id() || Gate::allows('isAdmin'), 403);

        $user = Users::findOrFail($id);
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = FileLibraryController::upload(
                $request->file('avatar'),
                'image',
                'users/avatar',
                'users',
                [
                    [35, 35, 'fit'], [46, 46, 'fit'], [63, 63, 'fit'],
                    [70, 70, 'fit'], [92, 92, 'fit'], [126, 126, 'fit'],
                    [246, 246, 'fit'], [600, 600, 'fit'],
                ]
            );
        }

        // role control
        if ($request->role === 'super_admin') {
            $data['role'] = 'user';
        } elseif (auth()->id() === $user->id) {
            $data['role'] = $user->role;
        }

        // password change
        if ($request->filled('password_change')) {
            $data['password'] = Hash::make($request->password_change);
        }

        $user->update($data);

        return back()->with('notification', [
            'class' => 'success',
            'message' => 'کاربر مورد نظر با موفقیت ویرایش شد.'
        ]);
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
