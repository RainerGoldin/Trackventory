<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item_Borrowed;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Item Borrowed", description: "Operations related to borrowed items tracking")]
class ItemBorrowedController extends Controller
{
    /**
     * Display a listing of the resource.
     */    #[OA\Get(
        path: "/api/item-borroweds",
        summary: "Get all borrowed items",
        description: "Retrieve a list of all borrowed items with search, sort, and pagination",
        tags: ["Item Borrowed"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "search",
                description: "Search term for borrow_date, return_date, or due_date",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "sort_by",
                description: "Field to sort by",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string", enum: ["id", "borrow_date", "return_date", "due_date", "quantity", "fine", "created_at"])
            ),
            new OA\Parameter(
                name: "sort_order",
                description: "Sort order",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string", enum: ["asc", "desc"], default: "asc")
            ),
            new OA\Parameter(
                name: "per_page",
                description: "Number of items per page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", minimum: 1, maximum: 100, default: 15)
            ),
            new OA\Parameter(
                name: "page",
                description: "Page number",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", minimum: 1, default: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Items borrowed retrieved successfully"),
                        new OA\Property(
                            property: "data", 
                            type: "array", 
                            items: new OA\Items(ref: "#/components/schemas/ItemBorrowed")
                        ),
                        new OA\Property(
                            property: "pagination",
                            type: "object",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer"),
                                new OA\Property(property: "last_page", type: "integer"),
                                new OA\Property(property: "per_page", type: "integer"),
                                new OA\Property(property: "total", type: "integer"),
                                new OA\Property(property: "from", type: "integer"),
                                new OA\Property(property: "to", type: "integer")
                            ]
                        )
                    ]
                )
            )
        ]
    )]    public function index(Request $request): JsonResponse
    {
        $query = Item_Borrowed::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('borrow_date', 'LIKE', "%{$search}%")
                  ->orWhere('return_date', 'LIKE', "%{$search}%")
                  ->orWhere('due_date', 'LIKE', "%{$search}%")
                  ->orWhere('quantity', 'LIKE', "%{$search}%")
                  ->orWhere('fine', 'LIKE', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['id', 'borrow_date', 'return_date', 'due_date', 'quantity', 'fine', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $itemsBorrowed = $query->paginate($perPage);

        return response()->json([
            'message' => 'Items borrowed retrieved successfully',
            'data' => $itemsBorrowed->items(),
            'pagination' => [
                'current_page' => $itemsBorrowed->currentPage(),
                'last_page' => $itemsBorrowed->lastPage(),
                'per_page' => $itemsBorrowed->perPage(),
                'total' => $itemsBorrowed->total(),
                'from' => $itemsBorrowed->firstItem(),
                'to' => $itemsBorrowed->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */    #[OA\Post(
        path: "/api/item-borroweds",
        summary: "Create a new borrowed item record",
        description: "Create a new record for borrowed item",
        tags: ["Item Borrowed"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["user_id", "item_id", "borrow_status_id", "quantity", "borrow_date", "due_date"],
                properties: [
                    new OA\Property(property: "user_id", type: "integer", example: 1),
                    new OA\Property(property: "item_id", type: "integer", example: 1),
                    new OA\Property(property: "borrow_status_id", type: "integer", example: 3),
                    new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 2),
                    new OA\Property(property: "borrow_date", type: "string", format: "date", example: "2024-01-15"),
                    new OA\Property(property: "return_date", type: "string", format: "date", nullable: true, example: null),
                    new OA\Property(property: "due_date", type: "string", format: "date", example: "2024-01-22"),
                    new OA\Property(property: "fine", type: "number", format: "float", minimum: 0, example: 0)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Item borrowed created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Item borrowed created successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/ItemBorrowed")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'item_id' => 'required|integer',
            'borrow_status_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
            'due_date' => 'required|date',
            'fine' => 'nullable|numeric|min:0'
        ]);

        $itemBorrowed = Item_Borrowed::create($validated);
        return response()->json([
            'message' => 'Item borrowed created successfully',
            'data' => $itemBorrowed
        ], 201);
    }

    /**
     * Display the specified resource.
     */    #[OA\Get(
        path: "/api/item-borroweds/{id}",
        summary: "Get a specific borrowed item",
        description: "Retrieve a specific borrowed item record by ID",
        tags: ["Item Borrowed"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Item borrowed ID",
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
                        new OA\Property(property: "message", type: "string", example: "Item borrowed retrieved successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/ItemBorrowed")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Item borrowed not found")
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $itemBorrowed = Item_Borrowed::findOrFail($id);
        return response()->json([
            'message' => 'Item borrowed retrieved successfully',
            'data' => $itemBorrowed
        ]);
    }

    /**
     * Update the specified resource in storage.
     */    #[OA\Put(
        path: "/api/item-borroweds/{id}",
        summary: "Update a borrowed item record",
        description: "Update an existing borrowed item record",
        tags: ["Item Borrowed"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Item borrowed ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "user_id", type: "integer", example: 1),
                    new OA\Property(property: "item_id", type: "integer", example: 1),
                    new OA\Property(property: "borrow_status_id", type: "integer", example: 4),
                    new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 2),
                    new OA\Property(property: "borrow_date", type: "string", format: "date", example: "2024-01-15"),
                    new OA\Property(property: "return_date", type: "string", format: "date", nullable: true, example: "2024-01-20"),
                    new OA\Property(property: "due_date", type: "string", format: "date", example: "2024-01-22"),
                    new OA\Property(property: "fine", type: "number", format: "float", minimum: 0, example: 0)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Item borrowed updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Item borrowed updated successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/ItemBorrowed")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Item borrowed not found"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $itemBorrowed = Item_Borrowed::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'integer',
            'item_id' => 'integer',
            'borrow_status_id' => 'integer',
            'quantity' => 'integer|min:1',
            'borrow_date' => 'date',
            'return_date' => 'nullable|date',
            'due_date' => 'date',
            'fine' => 'nullable|numeric|min:0'
        ]);

        $itemBorrowed->update($validated);
        return response()->json([
            'message' => 'Item borrowed updated successfully',
            'data' => $itemBorrowed
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */    #[OA\Delete(
        path: "/api/item-borroweds/{id}",
        summary: "Delete a borrowed item record",
        description: "Delete an existing borrowed item record",
        tags: ["Item Borrowed"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Item borrowed ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Item borrowed deleted successfully"
            ),
            new OA\Response(response: 404, description: "Item borrowed not found")
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $itemBorrowed = Item_Borrowed::findOrFail($id);
        $itemBorrowed->delete();
        return response()->json([
            'message' => 'Item borrowed deleted successfully'
        ], 204);
    }
}
