<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * AuthController controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => 'logout']);
    }

    /**
     * User registration
     *
     * @OA\Put(
     *     path="/api/register",
     *     operationId="/register",
     *     description="User Registration",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         description="User details to registered",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/NewUser")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration Succeess",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="code", type="number")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error: Parameter validation Failed.",
     *         @OA\MediaType(mediaType="application/json")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Error: Registration Failed.",
     *         @OA\MediaType(mediaType="application/json")
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'bail|required|min:3',
            'lastname'  => 'nullable|min:3',
            'email'     => 'bail|required|email:filter,rfc,dns|unique:users',
            'phone'     => 'nullable|numeric|between:8,16',
            'password'  => 'bail|required|alpha_num|min:4',
            'dob'       => 'nullable|before:'.\strtotime('now'),
            'gender'    => 'required|in:male,female'
        ]);

        $hash = Hash::make($request->input('password'));
        $data = $request->only([
            'firstname',
            'lastname',
            'email',
            'phone',
            'dob',
            'gender'
        ]);
        $data['password'] = $hash;

        //return \response()->json($data);

        if (User::create($data)) {
            $result = [
                'success' => true,
                'code'    => 201
            ];
        } else {
            $result = [
                'success' => false,
                'code'    => 404
            ];
        }

        return \response()->json($result, $result['code']);
    }

    /**
     * Authenticate user by email and password
     * and return current user token to use on Authorization header.
     *
     * @OA\Post(
     *     path="/api/login",
     *     operationId="/login",
     *     description="Authenticate User",
     *     tags={"auth"},
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User email address",
     *         required=true,
     *         @OA\Schema(type="string",minimum=6)
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User password",
     *         required=true,
     *         @OA\Schema(type="string",minimum=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Return JSON object with token for API authentication.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="boolean"),
     *                 @OA\Property(property="token", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error: Unauthorized.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="boolean")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error: Parameter validation Failed.",
     *         @OA\MediaType(mediaType="application/json")
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'bail|required|email:rfc,dns|exists:users',
            'password' => 'required|min:3'
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if ($user && Hash::check($request->input('password'), $user->password)) {
            $token = $user->generateApiToken();
            $user->update([
                'last_login' => date('Y-m-d H:i:s'),
                'api_token'  => $token
            ]);
            return \response()->json([
                'status' => true,
                'token'  => $token
            ]);
        }

        return \response()->json(['status' => false, 'message' => 'Invalid email or password'], 401);
    }

    /**
     * Logout current user.
     *
     * @OA\Get(
     *     path="/api/logout",
     *     operationId="/logout",
     *     description="User Logout",
     *     security="Authorization",
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Success or failure user logged out from status property.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="boolean")
     *             )
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->update(['api_token' => null]);
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

    /**
     * Check if email exists.
     *
     * @OA\Get(
     *     path="/api/exists",
     *     operationId="/exists",
     *     description="Check if email is exists",
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Email exists status from status property.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="boolean")
     *             )
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function exists(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $status = ($user && $user->active);
        return response()->json(['status' => $status]);
    }

    /**
     * Check if email unique.
     *
     * @OA\Get(
     *     path="/api/unique",
     *     operationId="/unique",
     *     description="Check if email is unique",
     *     tags={"auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Email unique status from status property.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="status", type="boolean")
     *             )
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function unique(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $status = !($user && $user->active);
        return response()->json(['status' => $status]);
    }
}
