<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     * @OA\Get(
     *      path="/api/movies",
     *      summary="Retrieve a list of movies",
     *      tags={"Movies"},
     *      @OA\Response(
     *          response=200,
     *          description="Successful response with a list of movies",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example=""),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="id", type="integer", example=1),
     *                      @OA\Property(property="title", type="string", example="Inception"),
     *                      @OA\Property(property="release_date", type="string", format="date", example="2010-07-16"),
     *                      @OA\Property(property="genre", type="string", example="Science Fiction"),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error response if there is an exception",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Error message here"),
     *              @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *          )
     *      ),
     *     @OA\Parameter(
     *           name="Authorization",
     *           in="header",
     *           description="Bearer token for authorization",
     *           required=true,
     *           @OA\Schema(
     *               type="string",
     *               example="Bearer your-access-token"
     *           )
     *       )
     *  )
     *
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'status' => true,
                'message' => "",
                'data' => Movie::all()
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ],400);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/movies",
     *     summary="Create a new movie",
     *     tags={"Movies"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name", "image", "published_date"},
     *                 @OA\Property(property="name", type="string", format="string", example="Inception"),
     *                 @OA\Property(property="image", type="file", format="binary", description="Movie image file"),
     *                 @OA\Property(property="published_date", type="string", format="integer", example="2010-07-16"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Movie created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Movie has been created"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Inception"),
     *                 @OA\Property(property="image", type="string", example="/storage/images/1629208800.jpg"),
     *                 @OA\Property(property="published_date", type="string", format="date", example="2010-07-16"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-28T12:34:56Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-28T12:34:56Z"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error response if there is a validation error or exception",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message here"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *         )
     *     ),@OA\Parameter(
     *           name="Authorization",
     *           in="header",
     *           description="Bearer token for authorization",
     *           required=true,
     *           @OA\Schema(
     *               type="string",
     *               example="Bearer your-access-token"
     *           )
     *       )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required',
                'published_date' => 'required|integer',
            ]);
            // Handle file upload
            if ($request->hasFile('image')) {
                // Get the uploaded file
                $file = $request->file('image');

                // Define a unique name for the file
                $fileName = time() . '.' . $file->getClientOriginalExtension();

                // Move the file to the public storage directory
                $filePath = $file->storeAs('images', $fileName, 'public');
            } else {
                $filePath = null; // Handle the case where there is no file
            }

            // Create the movie record
            $movie = Movie::create([
                'name' => $request->name,
                'image' => $filePath, // Store the file path
                'published_date' => $request->published_date,
            ]);

            return response()->json([
                'status' => true,
                'message' => "Movie has been created",
                'data' => $movie
            ], 201);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @OA\Put(
     *      path="/api/movies/{id}",
     *      summary="Update an existing movie",
     *      tags={"Movies"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the movie to update",
     *          required=true,
     *          @OA\Schema(type="integer", example=1)
     *      ),
     *      @OA\RequestBody(
     *          required=false,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"name","published_date"},
     *                  @OA\Property(property="name", type="string", format="string", example="Inception"),
     *                  @OA\Property(property="image", type="file", format="binary", description="Movie image file"),
     *                  @OA\Property(property="published_date", type="string", format="integer", example="2010-07-16"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Movie updated successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Movie has been updated"),
     *              @OA\Property(
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="name", type="string", example="Inception"),
     *                  @OA\Property(property="image", type="string", example="/storage/images/1629208800.jpg"),
     *                  @OA\Property(property="published_date", type="string", format="date", example="2010-07-16"),
     *                  @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-28T12:34:56Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-28T12:34:56Z"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error response if there is a validation error or exception",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Error message here"),
     *              @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *          )
     *      ),
     *     @OA\Parameter(
     *           name="Authorization",
     *           in="header",
     *           description="Bearer token for authorization",
     *           required=true,
     *           @OA\Schema(
     *               type="string",
     *               example="Bearer your-access-token"
     *           )
     *       )
     *  )
     */
    public function update(Request $request, Movie $movie): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'published_date' => 'sometimes|required|integer',
            ]);
            $data = [
                'name' => $request->name,
                'published_date' => $request->published_date
            ];
            if ($request->hasFile('image')) {
                // Get the uploaded file
                $file = $request->file('image');

                // Define a unique name for the file
                $fileName = time() . '.' . $file->getClientOriginalExtension();

                // Move the file to the public storage directory
                $filePath = $file->storeAs('images', $fileName, 'public');
                $data['image'] = $filePath;
            }
            $movie->update($data);

            return response()->json([
                'status' => true,
                'message' => "Movie has been updated",
                'data' => $movie
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
                'data' => []
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie): JsonResponse
    {
        try {
            $movie->delete();
            return response()->json([
                'status' => true,
                'message' => "Movie has been deleted",
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
