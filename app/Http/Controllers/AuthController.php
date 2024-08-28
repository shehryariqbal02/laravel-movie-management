<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle login requests.
     * @OA\Info(
     *      title="Movies API",
     *      version="1.0.0",
     *      description="The Movies API allows you to manage a collection of movies with CRUD operations. It supports creating, retrieving, updating, and deleting movie records, including uploading and managing cover images.",
     *      @OA\Contact(
     *          email="support@example.com"
     *      ),
     *      @OA\License(
     *          name="MIT",
     *          url="https://opensource.org/licenses/MIT"
     *      )
     *  ),
    *  @OA\Post(
    *      path = "/api/login",
    *      summary = "User login",
    *      tags = {"Authentication"},
     * @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "application/json",
     *              @OA\Schema(
     *                  type = "object",
     *                  required ={"email", "password"},
     *                  @OA\Property(property = "email", type = "string", format = "email", example = "user@example.com"),
     *                  @OA\Property(property = "password", type = "string", format = "password", example = "yourpassword"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response = 200,
     *          description = "Successful login",
     *          @OA\JsonContent(
     *              @OA\Property(property = "user", type = "object",
     *                  @OA\Property(property = "id", type = "integer", example = 1),
     *                  @OA\Property(property = "email", type = "string", example = "user@example.com"),
     *                  @OA\Property(property = "token", type = "string", example = "your-access-token"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response = 422,
     *          description = "Validation errors",
     *          @OA\JsonContent(
     *              @OA\Property(property = "errors", type = "object",
     *                  @OA\Property(property = "email", type = "array", @OA\Items(type = "string")),
     *                  @OA\Property(property = "password", type = "array", @OA\Items(type = "string")),
     *              ),
     *              @OA\Property(property = "message", type = "string", example = "The provided credentials are incorrect."),
     *          )
     *      ),
     *      @OA\Response(
     *          response = 401,
     *          description = "Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property = "message", type = "string", example = "The provided credentials are incorrect."),
     *          )
     *      ),
     *  )
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->token = $user->createToken('Personal Access Token')->plainTextToken;

            return response()->json(['user' => $user], 200);
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
    /**
     * @OA\Post(
     *      path="/api/logout",
     *      summary="Log out the authenticated user",
     *      tags={"Authentication"},
     *      security={
     *          {"apiAuth": {}}
     *      },
     *      @OA\Response(
     *          response=200,
     *          description="Successful logout",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Logged out successfully.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Unauthorized")
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer token for authorization",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *              example="Bearer your-access-token"
     *          )
     *      )
     *  )
     * Handle logout and invalidate the token.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();
        // Revoke the token (for Sanctum, the token is automatically revoked when it's deleted)
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }
    /**
     * Check if the user is authenticated.
     * @param Request $request
     * @return JsonResponse
     */
    public function checkAuth(Request $request): JsonResponse
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            return response()->json([
                'authenticated' => true,
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'authenticated' => false,
        ], 401);
    }
}
