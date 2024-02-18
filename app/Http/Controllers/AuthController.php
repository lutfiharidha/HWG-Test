<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Borrowing;
use App\Http\Resources\BorrowingResource;

class AuthController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(Request $request)
    {
        try{
            $this->validate($request, [
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|string|email:rfc,dns|max:255|unique:users',
                'password' => 'required|string|min:6|max:255',
            ]);

            $user = $this->user::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => bcrypt($request['password']),
            ]);
            $token = auth()->login($user);

            return response()->json(['data' => [
                    'user' => $user,
                    'access_token' => [
                        'token' => $token,
                        'type' => 'Bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60,    // get token expires in seconds
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function login(Request $request)
    {
        try{
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string',
            ]);

            $token = auth()->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($token)
            {
                return response()->json([
                    'data' => [
                        'user' => auth()->user(),
                        'access_token' => [
                            'token' => $token,
                            'type' => 'Bearer',
                            'expires_in' => auth()->factory()->getTTL() * 60,
                        ],
                    ],
                ]);
            }
            return response()->json(['message' => 'failed log in check email or password']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function me()
    {
        try{
            $borrows = Borrowing::where("user_id", auth()->user()->id)->get();
            return response()->json([
                'data' => [
                    'user' => auth()->user(),
                    'books' => BorrowingResource::collection($borrows)
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function logout()
    {
        // get token
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);

        if($invalidate) {
            return response()->json(['message' => 'Successfully logged out']);
        }

        return response()->json(['message' => 'Failed logged out']);
    }
}
