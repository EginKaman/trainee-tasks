<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\{NewUser, UpdateUser};
use App\Http\Requests\{IndexUserRequest, StoreUserRequest, UpdateUserRequest};
use App\Http\Resources\{UserCollection, UserResource};
use App\Models\User;
use Illuminate\Http\{Request, Response};

class UserController extends Controller
{
    public function index(IndexUserRequest $request): UserCollection
    {
        return new UserCollection(
            User::query()->with('role')->paginate(
                perPage: $request->validated('per_page'),
                page: $request->validated('page')
            )
        );
    }

    public function store(StoreUserRequest $request, NewUser $newUser): UserResource
    {
        $user = $newUser->store($request);

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user->load('role'));
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUser $updateUser): UserResource
    {
        return new UserResource($updateUser->update($request, $user));
    }

    public function destroy(User $user): Response
    {
        if ($user->delete()) {
            return response(__('Deleted success'), 204);
        }

        return response(__('Something went wrong'), 503);
    }
}
