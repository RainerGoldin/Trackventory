<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Roles", description: "Operations related to user role management")]
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */    #[OA\Get(
        path: "/api/roles",
        summary: "Get all roles",
        description: "Retrieve a list of all user roles",
        tags: ["Roles"],
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful operation",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "roles", 
                            type: "array", 
                            items: new OA\Items(ref: "#/components/schemas/Role")
                        )
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */    #[OA\Post(
        path: "/api/roles",
        summary: "Create a new role",
        description: "Create a new user role",
        tags: ["Roles"],
        security: [["bearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["role_name"],
                properties: [
                    new OA\Property(property: "role_name", type: "string", maxLength: 255, example: "Manager"),
                    new OA\Property(property: "description", type: "string", example: "Department manager role")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Role created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "role", ref: "#/components/schemas/Role"),
                        new OA\Property(property: "message", type: "string", example: "Role created successfully")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);
        return response()->json(['role' => $role, 'message' => 'Role created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */    #[OA\Get(
        path: "/api/roles/{id}",
        summary: "Get a specific role",
        description: "Retrieve a specific role by ID",
        tags: ["Roles"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Role ID",
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
                        new OA\Property(property: "role", ref: "#/components/schemas/Role")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Role not found")
        ]
    )]
    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        return response()->json(['role' => $role]);
    }

    /**
     * Update the specified resource in storage.
     */    #[OA\Put(
        path: "/api/roles/{id}",
        summary: "Update a role",
        description: "Update an existing role",
        tags: ["Roles"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Role ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["role_name"],
                properties: [
                    new OA\Property(property: "role_name", type: "string", maxLength: 255, example: "Senior Manager"),
                    new OA\Property(property: "description", type: "string", example: "Senior manager role with additional privileges")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Role updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "role", ref: "#/components/schemas/Role"),
                        new OA\Property(property: "message", type: "string", example: "Role updated successfully")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Role not found"),
            new OA\Response(response: 422, description: "Validation error")
        ]
    )]
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        
        $validated = $request->validate([
            'role_name' => 'required|string|max:255|unique:roles,role_name,'.$id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);
        return response()->json(['role' => $role, 'message' => 'Role updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */    #[OA\Delete(
        path: "/api/roles/{id}",
        summary: "Delete a role",
        description: "Delete an existing role",
        tags: ["Roles"],
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                description: "Role ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Role deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Role deleted successfully")
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: "Cannot delete role with associated users",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Cannot delete role with associated users")
                    ]
                )
            ),
            new OA\Response(response: 404, description: "Role not found")
        ]
    )]
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        
        // Check if users are using this role
        if ($role->users()->count() > 0) {
            return response()->json(['message' => 'Cannot delete role with associated users'], 409);
        }
        
        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }
}
