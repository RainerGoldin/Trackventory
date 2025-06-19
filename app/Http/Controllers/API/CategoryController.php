<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Categories", description: "Operations related to category management")]
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */    #[OA\Get(
        path: "/api/categories",
        summary: "Get all categories",
        description: "Retrieve a list of all categories",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Categories retrieved successfully"),
                        new OA\Property(
                            property: "data", 
                            type: "array", 
                            items: new OA\Items(ref: "#/components/schemas/Category")
                        )
                    ]
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $categories = Category::all();
        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */    #[OA\Post(
        path: "/api/categories",
        summary: "Create a new category",
        description: "Create a new category with name and description",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["category_name"],
                properties: [
                    new OA\Property(property: "category_name", type: "string", maxLength: 255, example: "Electronics"),
                    new OA\Property(property: "description", type: "string", example: "Electronic devices and components")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Category created successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Category")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->only(['category_name', 'description']));
        
        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */    #[OA\Get(
        path: "/api/categories/{id}",
        summary: "Get a specific category",
        description: "Retrieve a specific category by ID",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Category ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Category retrieved successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Category")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Category not found")
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'message' => 'Category retrieved successfully',
            'data' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */    #[OA\Put(
        path: "/api/categories/{id}",
        summary: "Update a category",
        description: "Update an existing category",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Category ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["category_name"],
                properties: [
                    new OA\Property(property: "category_name", type: "string", maxLength: 255, example: "Office Supplies"),
                    new OA\Property(property: "description", type: "string", example: "Office supplies and stationery")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Category updated successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Category")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Category not found"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->only(['category_name', 'description']));
        
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */    #[OA\Delete(
        path: "/api/categories/{id}",
        summary: "Delete a category",
        description: "Delete an existing category",
        tags: ["Categories"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Category ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Category deleted successfully"
            ),
            new OA\Response(response: 404, description: "Category not found")
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        
        return response()->json([
            'message' => 'Category deleted successfully'
        ], 204);
    }
}
