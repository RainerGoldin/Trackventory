<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrow_Status;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Borrow Statuses", description: "Operations related to borrow status management")]
class BorrowStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */    #[OA\Get(
        path: "/api/borrow-statuses",
        summary: "Get all borrow statuses",
        description: "Retrieve a list of all borrow statuses",
        tags: ["Borrow Statuses"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Borrow statuses retrieved successfully"),
                        new OA\Property(
                            property: "data", 
                            type: "array", 
                            items: new OA\Items(ref: "#/components/schemas/BorrowStatus")
                        )
                    ]
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $borrowStatuses = Borrow_Status::all();
        return response()->json([
            'message' => 'Borrow statuses retrieved successfully',
            'data' => $borrowStatuses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */    #[OA\Post(
        path: "/api/borrow-statuses",
        summary: "Create a new borrow status",
        description: "Create a new borrow status with status name and badge color",
        tags: ["Borrow Statuses"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["status_name", "badge_color"],
                properties: [
                    new OA\Property(property: "status_name", type: "string", maxLength: 255, example: "Borrowed"),
                    new OA\Property(property: "badge_color", type: "string", maxLength: 7, example: "#007BFF")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Borrow status created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Borrow status created successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/BorrowStatus")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'status_name' => 'required|string|max:255',
            'badge_color' => 'required|string|max:7'
        ]);

        $borrowStatus = Borrow_Status::create($request->only(['status_name', 'badge_color']));
        
        return response()->json([
            'message' => 'Borrow status created successfully',
            'data' => $borrowStatus
        ], 201);
    }

    /**
     * Display the specified resource.
     */    #[OA\Get(
        path: "/api/borrow-statuses/{id}",
        summary: "Get a specific borrow status",
        description: "Retrieve a specific borrow status by ID",
        tags: ["Borrow Statuses"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Borrow status ID",
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
                        new OA\Property(property: "message", type: "string", example: "Borrow status retrieved successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/BorrowStatus")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Borrow status not found")
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $borrowStatus = Borrow_Status::findOrFail($id);
        return response()->json([
            'message' => 'Borrow status retrieved successfully',
            'data' => $borrowStatus
        ]);
    }

    /**
     * Update the specified resource in storage.
     */    #[OA\Put(
        path: "/api/borrow-statuses/{id}",
        summary: "Update a borrow status",
        description: "Update an existing borrow status",
        tags: ["Borrow Statuses"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Borrow status ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "status_name", type: "string", maxLength: 255, example: "Returned"),
                    new OA\Property(property: "badge_color", type: "string", maxLength: 7, example: "#28A745")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Borrow status updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Borrow status updated successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/BorrowStatus")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Borrow status not found"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'status_name' => 'sometimes|string|max:255',
            'badge_color' => 'sometimes|string|max:7'
        ]);

        $borrowStatus = Borrow_Status::findOrFail($id);
        $borrowStatus->update($request->only(['status_name', 'badge_color']));
        
        return response()->json([
            'message' => 'Borrow status updated successfully',
            'data' => $borrowStatus
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */    #[OA\Delete(
        path: "/api/borrow-statuses/{id}",
        summary: "Delete a borrow status",
        description: "Delete an existing borrow status",
        tags: ["Borrow Statuses"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Borrow status ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Borrow status deleted successfully"
            ),
            new OA\Response(response: 404, description: "Borrow status not found")
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $borrowStatus = Borrow_Status::findOrFail($id);
        $borrowStatus->delete();
        
        return response()->json([
            'message' => 'Borrow status deleted successfully'
        ], 204);
    }
}
