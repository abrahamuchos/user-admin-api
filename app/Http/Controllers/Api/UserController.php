<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(
            User::query()->orderBy('id', 'desc')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     *
     * @return Response
     */
    public function store(StoreUserRequest $request): Response
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        //Todo: Forza un error para ver como se comporta SQL
        try {
            $user = User::create($data);
            return response(new UserResource($user), 201);

        } catch (QueryException $e) {
            return response([
                'error' => true,
                'code' => $e->getCode(),
                'message' => $e->errorInfo,
                'trace' => $e->getTrace()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return UserResource
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User              $user
     *
     * @return UserResource|Response
     */
    public function update(UpdateUserRequest $request, User $user): UserResource|Response
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        try {
            $user->update($data);
            return new UserResource($user);
        } catch (QueryException $e) {
            return response([
                'error' => true,
                'code' => $e->getCode(),
                'message' => $e->errorInfo,
                'trace' => $e->getTrace()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return Response
     */
    public function destroy(User $user): Response
    {
        try {
            $user->delete();
            return response('', 200);

        } catch (QueryException $e) {
            return response([
                'error' => true,
                'code' => $e->getCode(),
                'message' => $e->errorInfo,
                'trace' => $e->getTrace()
            ], 500);
        }
    }
}
