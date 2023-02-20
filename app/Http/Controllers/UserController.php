<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\User\{NewUser, UpdateUser};
use App\Http\Requests\{IndexUserRequest, StoreUserRequest};
use App\Http\Resources\{UserCollection, UserResource};
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\{Request, Response};

class UserController extends Controller
{
    public function index(IndexUserRequest $request): AnonymousResourceCollection
    {
        return UserCollection::collection(
            User::query()->paginate(perPage: $request->validated('per_page'), page: $request->validated('page'))
        );
    }

    public function store(StoreUserRequest $request, NewUser $newUser): UserResource
    {
        $user = $newUser->store($request);

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(StoreUserRequest $request, UpdateUser $updateUser, User $user): UserResource
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
