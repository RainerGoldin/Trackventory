<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Items", description: "Operations related to inventory item management")]
class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */    #[OA\Get(
        path: "/api/items",
        summary: "Get all items",
        description: "Retrieve a list of all inventory items with search, sort, and pagination",
        tags: ["Items"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "search",
                description: "Search term for item name, description, or brand",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string")
            ),
            new OA\Parameter(
                name: "sort_by",
                description: "Field to sort by",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "string", enum: ["id", "item_name", "stock", "brand", "created_at"])
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
                        new OA\Property(property: "message", type: "string", example: "Items retrieved successfully"),
                        new OA\Property(
                            property: "data", 
                            type: "array", 
                            items: new OA\Items(ref: "#/components/schemas/Item")
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
        $query = Item::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['id', 'item_name', 'stock', 'brand', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $items = $query->paginate($perPage);

        return response()->json([
            'message' => 'Items retrieved successfully',
            'data' => $items->items(),
            'pagination' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
                'from' => $items->firstItem(),
                'to' => $items->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */    #[OA\Post(
        path: "/api/items",
        summary: "Create a new item",
        description: "Create a new inventory item",
        tags: ["Items"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["category_id", "item_name", "stock"],
                properties: [
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "item_name", type: "string", maxLength: 255, example: "Laptop Dell Inspiron"),
                    new OA\Property(property: "stock", type: "integer", minimum: 0, example: 25)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Item created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Item created successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Item")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => 'required|integer',
            'item_name' => 'required|string|max:255',
            'stock' => 'required|integer|min:0'
        ]);

        $item = Item::create($request->only(['category_id', 'item_name', 'stock']));
        
        return response()->json([
            'message' => 'Item created successfully',
            'data' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */    #[OA\Get(
        path: "/api/items/{id}",
        summary: "Get a specific item",
        description: "Retrieve a specific inventory item by ID",
        tags: ["Items"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Item ID",
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
                        new OA\Property(property: "message", type: "string", example: "Item retrieved successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Item")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Item not found")
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        return response()->json([
            'message' => 'Item retrieved successfully',
            'data' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     */    #[OA\Put(
        path: "/api/items/{id}",
        summary: "Update an item",
        description: "Update an existing inventory item",
        tags: ["Items"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Item ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                    new OA\Property(property: "item_name", type: "string", maxLength: 255, example: "Updated Item Name"),
                    new OA\Property(property: "stock", type: "integer", minimum: 0, example: 30)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Item updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Item updated successfully"),
                        new OA\Property(property: "data", ref: "#/components/schemas/Item")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Item not found"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'category_id' => 'sometimes|required|integer',
            'item_name' => 'sometimes|required|string|max:255',
            'stock' => 'sometimes|required|integer|min:0'
        ]);

        $item = Item::findOrFail($id);
        $item->update($request->only(['category_id', 'item_name', 'stock']));
        
        return response()->json([
            'message' => 'Item updated successfully',
            'data' => $item
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */    #[OA\Delete(
        path: "/api/items/{id}",
        summary: "Delete an item",
        description: "Delete an existing inventory item",
        tags: ["Items"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Item ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Item deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Item deleted successfully")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Item not found")
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        $item->delete();
        
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
