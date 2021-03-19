<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use App\Utils\Consts\ExecResult;
use Exception;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(20);

        return view('admin.user.index', [
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = new User();
        return view('admin.user.form', [
            'user' => $user,
            'formOptions' => [
                'route' => ['admin.user.store',],
                'method' => 'post',
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Admin\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $status = ExecResult::FAILURE;
        try {
            User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'is_admin' => isset($data['is_admin']) ? $data['is_admin']:false,
                'password' => Hash::make($data['password']),
            ]);
            $status = ExecResult::SUCCESS;
            $message = '登録が完了しました';
        } catch (Exception $e) {
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
        }

        return redirect(route('admin.user.index'))->with($status, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.user.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.user.form', [
            'user' => $user,
            'formOptions' => [
                'route' => ['admin.user.update', [$user->id]],
                'method' => 'put',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Admin\UpdateUserRequest  $request
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $status = ExecResult::FAILURE;
        $data = $request->all();
        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_admin' => isset($data['is_admin']) ? $data['is_admin']:false,
            'password' => $data['password'] !== null ? Hash::make($data['password']) : $user->password,
        ]);

        try {
            $user->save();
            $status = ExecResult::SUCCESS;
            $message = '更新しました';
        } catch (Exception $e) {
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
        }
        return redirect(route('admin.user.index'))->with($status, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $status = ExecResult::FAILURE;
        try {
            $user->delete();
            $status = ExecResult::SUCCESS;
            $message = '削除しました';
        } catch (Exception $e) {
            $status = ExecResult::FAILURE;
            $message = $e->getMessage();
        }
        return redirect(route('admin.user.index'))->with($status, $message);
    }
}
