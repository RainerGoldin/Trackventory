<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Purchase_Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Purchase Invoices",
 *     description="Purchase invoices management"
 * )
 */
class PurchaseInvoiceController extends Controller
{    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/purchase-invoices",
     *     operationId="getPurchaseInvoicesList",
     *     tags={"Purchase Invoices"},
     *     summary="Get list of purchase invoices",
     *     description="Retrieve all purchase invoices with search, sort, and pagination",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="search",
     *         description="Search term for item purchased",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_by",
     *         description="Field to sort by",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="string", enum={"id", "item_purchased", "total_price", "budget", "quantity", "created_at"})
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
     *         description="Purchase invoices retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase invoices retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/PurchaseInvoice")
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
        $query = Purchase_Invoice::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('item_purchased', 'LIKE', "%{$search}%");
        }

        // Sorting functionality
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['id', 'item_purchased', 'total_price', 'budget', 'quantity', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $purchaseInvoices = $query->paginate($perPage);

        return response()->json([
            'message' => 'Purchase invoices retrieved successfully',
            'data' => $purchaseInvoices->items(),
            'pagination' => [
                'current_page' => $purchaseInvoices->currentPage(),
                'last_page' => $purchaseInvoices->lastPage(),
                'per_page' => $purchaseInvoices->perPage(),                'total' => $purchaseInvoices->total(),
                'from' => $purchaseInvoices->firstItem(),
                'to' => $purchaseInvoices->lastItem(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/purchase-invoices",
     *     operationId="storePurchaseInvoice",
     *     tags={"Purchase Invoices"},
     *     summary="Create a new purchase invoice",     *     description="Store a newly created purchase invoice",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseInvoiceStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Purchase invoice created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase invoice created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PurchaseInvoice")
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
            'item_purchased' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'budget' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'img' => 'nullable|string'
        ]);

        $purchaseInvoice = Purchase_Invoice::create($validated);
        return response()->json([
            'message' => 'Purchase invoice created successfully',
            'data' => $purchaseInvoice
        ], 201);
    }    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/purchase-invoices/{id}",
     *     operationId="getPurchaseInvoice",
     *     tags={"Purchase Invoices"},
     *     summary="Get purchase invoice information",     *     description="Retrieve a specific purchase invoice by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase invoice ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Purchase invoice retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase invoice retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PurchaseInvoice")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase invoice not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $purchaseInvoice = Purchase_Invoice::findOrFail($id);
        return response()->json([
            'message' => 'Purchase invoice retrieved successfully',
            'data' => $purchaseInvoice
        ]);
    }    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/purchase-invoices/{id}",
     *     operationId="updatePurchaseInvoice",
     *     tags={"Purchase Invoices"},
     *     summary="Update purchase invoice",     *     description="Update the specified purchase invoice",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase invoice ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PurchaseInvoiceUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Purchase invoice updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Purchase invoice updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/PurchaseInvoice")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase invoice not found"
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
        $purchaseInvoice = Purchase_Invoice::findOrFail($id);
        
        $validated = $request->validate([
            'item_purchased' => 'string|max:255',
            'total_price' => 'numeric|min:0',
            'budget' => 'numeric|min:0',
            'quantity' => 'integer|min:1',
            'img' => 'nullable|string'
        ]);

        $purchaseInvoice->update($validated);
        return response()->json([
            'message' => 'Purchase invoice updated successfully',
            'data' => $purchaseInvoice
        ]);
    }    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/purchase-invoices/{id}",
     *     operationId="deletePurchaseInvoice",
     *     tags={"Purchase Invoices"},
     *     summary="Delete purchase invoice",     *     description="Remove the specified purchase invoice",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Purchase invoice ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Purchase invoice deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Purchase invoice not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $purchaseInvoice = Purchase_Invoice::findOrFail($id);
        $purchaseInvoice->delete();
        return response()->json([
            'message' => 'Purchase invoice deleted successfully'
        ], 204);
    }
}
