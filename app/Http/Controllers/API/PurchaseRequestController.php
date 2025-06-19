<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase_Request;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Purchase Requests",
 *     description="Purchase requests management"
 * )
 */
class PurchaseRequestController extends Controller
{    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/purchase-requests",
     *     operationId="getPurchaseRequestsList",
     *     tags={"Purchase Requests"},
     *     summary="Get list of purchase requests",
     *     description="Retrieve all purchase requests with search, sort, and pagination",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         description="Search term for item requested or description",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         description="Field to sort by",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="string", enum={"id", "item_requested", "quantity", "price_per_pcs", "request_date", "approved_budget", "used_budget", "created_at"})
     *     ),
     *     @OA\Parameter(
     *         name="sort_order",
     *         description="Sort order",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         description="Number of items per page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=15)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="Page number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Purchase requests retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase requests retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PurchaseRequest")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="last_page", type="integer"),
     *                 @OA\Property(property="per_page", type="integer"),
     *                 @OA\Property(property="total", type="integer"),
     *                 @OA\Property(property="from", type="integer"),
     *                 @OA\Property(property="to", type="integer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */    public function index(Request $request): JsonResponse
    {
        $query = Purchase_Request::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_requested', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['id', 'item_requested', 'quantity', 'price_per_pcs', 'request_date', 'approved_budget', 'used_budget', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $purchaseRequests = $query->paginate($perPage);

        return response()->json([
            'message' => 'Purchase requests retrieved successfully',
            'data' => $purchaseRequests->items(),
            'pagination' => [
                'current_page' => $purchaseRequests->currentPage(),
                'last_page' => $purchaseRequests->lastPage(),
                'per_page' => $purchaseRequests->perPage(),
                'total' => $purchaseRequests->total(),
                'from' => $purchaseRequests->firstItem(),
                'to' => $purchaseRequests->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/purchase-requests",
     *     operationId="storePurchaseRequest",
     *     tags={"Purchase Requests"},
     *     summary="Create a new purchase request",     *     description="Store a newly created purchase request",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseRequestStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Purchase request created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase request created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PurchaseRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'item_requested' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'price_per_pcs' => 'required|numeric|min:0',
            'request_date' => 'required|date',
            'approved_budget' => 'nullable|numeric|min:0',
            'used_budget' => 'nullable|numeric|min:0'
        ]);

        $purchaseRequest = Purchase_Request::create($validated);
        return response()->json([
            'message' => 'Purchase request created successfully',
            'data' => $purchaseRequest
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/purchase-requests/{id}",
     *     operationId="getPurchaseRequest",
     *     tags={"Purchase Requests"},
     *     summary="Get purchase request information",     *     description="Retrieve a specific purchase request by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase request ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Purchase request retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase request retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PurchaseRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase request not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $purchaseRequest = Purchase_Request::findOrFail($id);
        return response()->json([
            'message' => 'Purchase request retrieved successfully',
            'data' => $purchaseRequest
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/purchase-requests/{id}",
     *     operationId="updatePurchaseRequest",
     *     tags={"Purchase Requests"},
     *     summary="Update purchase request",     *     description="Update the specified purchase request",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase request ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseRequestUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Purchase request updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase request updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PurchaseRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase request not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $purchaseRequest = Purchase_Request::findOrFail($id);
        
        $validated = $request->validate([
            'item_requested' => 'string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'integer|min:1',
            'price_per_pcs' => 'numeric|min:0',
            'request_date' => 'date',
            'approved_budget' => 'nullable|numeric|min:0',
            'used_budget' => 'nullable|numeric|min:0'
        ]);

        $purchaseRequest->update($validated);
        return response()->json([
            'message' => 'Purchase request updated successfully',
            'data' => $purchaseRequest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/purchase-requests/{id}",
     *     operationId="deletePurchaseRequest",
     *     tags={"Purchase Requests"},
     *     summary="Delete purchase request",     *     description="Remove the specified purchase request",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase request ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Purchase request deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase request not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $purchaseRequest = Purchase_Request::findOrFail($id);
        $purchaseRequest->delete();
        return response()->json([
            'message' => 'Purchase request deleted successfully'
        ], 204);
    }
}
