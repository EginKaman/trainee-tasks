<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\{NewUser, UpdateUser};
use App\Http\Requests\{IndexUserRequest, StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\{UserCollection, UserResource};
use App\Models\User;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index(IndexUserRequest $request): UserCollection
    {
        $per_page = $request->validated('per_page', 6);
        $page = $request->validated('page', 1);

        return Cache::tags('users')->rememberForever(
            "users.index.{$per_page}.{$page}",
            fn () => new UserCollection(User::query()->with('role')->latest('created_at')->paginate(
                perPage: $per_page,
                page: $page
            ))
        );
    }

    public function store(StoreUserRequest $request, NewUser $newUser): UserResource
    {
        $user = $newUser->store($request);

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return Cache::tags('users')->rememberForever(
            "users.show.{$user->id}",
            fn () => new UserResource($user->load('role'))
        );
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUser $updateUser): UserResource
    {
        return new UserResource($updateUser->update($request, $user));
    }

    public function destroy(User $user): Response
    {
        if ($user->delete()) {
            Cache::tags('users')->flush();

            return response(__('Deleted success'), 204);
        }

        return response(__('Something went wrong'), 503);
    }
}
