<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get current User Details.
     *
     * @OA\Get(
     *     path="/api/user",
     *     operationId="/user",
     *     description="Get User Details",
     *     security="Authorization",
     *     tags={"user"},
     *     @OA\Response(
     *         response=200,
     *         description="Success fetching user data object.",
     *         @OA\JsonContent(ref="#/components/schemas/User"),
     *     ),
     *     @OA\Response(response=401, description="Error: Unauthorized.")
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return $request->user();
    }
}
