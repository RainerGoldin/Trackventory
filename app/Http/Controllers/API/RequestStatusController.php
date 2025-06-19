<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Request_Status;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Request Statuses",
 *     description="Request statuses management"
 * )
 */
class RequestStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/request-statuses",
     *     operationId="getRequestStatusesList",
     *     tags={"Request Statuses"},
     *     summary="Get list of request statuses",     *     description="Retrieve all request statuses",
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Request statuses retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Request statuses retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/RequestStatus")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $requestStatuses = Request_Status::all();
        return response()->json([
            'message' => 'Request statuses retrieved successfully',
            'data' => $requestStatuses
        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post(
     *     path="/api/request-statuses",
     *     operationId="storeRequestStatus",
     *     tags={"Request Statuses"},
     *     summary="Create a new request status",     *     description="Store a newly created request status",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RequestStatusStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Request status created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Request status created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RequestStatus")
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
            'status_name' => 'required|string|max:255',
            'badge_color' => 'required|string|max:255'
        ]);

        $requestStatus = Request_Status::create($validated);
        return response()->json([
            'message' => 'Request status created successfully',
            'data' => $requestStatus
        ], 201);
    }    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *     path="/api/request-statuses/{id}",
     *     operationId="getRequestStatus",
     *     tags={"Request Statuses"},
     *     summary="Get request status information",     *     description="Retrieve a specific request status by ID",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Request status ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request status retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Request status retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RequestStatus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Request status not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $requestStatus = Request_Status::findOrFail($id);
        return response()->json([
            'message' => 'Request status retrieved successfully',
            'data' => $requestStatus
        ]);
    }    /**
     * Update the specified resource in storage.
     *
     * @OA\Put(
     *     path="/api/request-statuses/{id}",
     *     operationId="updateRequestStatus",
     *     tags={"Request Statuses"},
     *     summary="Update request status",     *     description="Update the specified request status",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Request status ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RequestStatusUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Request status updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Request status updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/RequestStatus")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Request status not found"
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
        $validated = $request->validate([
            'status_name' => 'sometimes|required|string|max:255',
            'badge_color' => 'sometimes|required|string|max:255'
        ]);

        $requestStatus = Request_Status::findOrFail($id);
        $requestStatus->update($validated);
        return response()->json([
            'message' => 'Request status updated successfully',
            'data' => $requestStatus
        ]);
    }    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete(
     *     path="/api/request-statuses/{id}",
     *     operationId="deleteRequestStatus",
     *     tags={"Request Statuses"},
     *     summary="Delete request status",     *     description="Remove the specified request status",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         description="Request status ID",
     *         required=true,
     *         in="path",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Request status deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Request status not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $requestStatus = Request_Status::findOrFail($id);
        $requestStatus->delete();
        return response()->json([
            'message' => 'Request status deleted successfully'
        ], 204);
    }
}
