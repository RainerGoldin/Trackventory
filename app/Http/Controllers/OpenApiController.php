<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Trackventory API",
    description: "API documentation for Trackventory - Inventory Tracking System",
    contact: new OA\Contact(
        name: "Trackventory Support",
        email: "support@trackventory.com"
    )
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local development server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Token",
    description: "Enter token in format: Bearer {token}"
)]
#[OA\Schema(
    schema: "User",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "name", type: "string", example: "John Doe"),
        new OA\Property(property: "email", type: "string", format: "email", example: "john@example.com"),
        new OA\Property(property: "role_id", type: "integer", example: 3),
        new OA\Property(property: "email_verified_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "Role",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "role_name", type: "string", example: "Admin"),
        new OA\Property(property: "description", type: "string", example: "System administrator"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "Category",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "category_name", type: "string", example: "Electronics"),
        new OA\Property(property: "description", type: "string", example: "Electronic devices and components"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "Item",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "category_id", type: "integer", example: 1),
        new OA\Property(property: "item_name", type: "string", example: "Laptop Dell Inspiron"),
        new OA\Property(property: "stock", type: "integer", example: 25),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "BorrowStatus",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "status_name", type: "string", example: "Borrowed"),
        new OA\Property(property: "badge_color", type: "string", example: "#007BFF"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "RequestStatus",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "status_name", type: "string", example: "Approved"),
        new OA\Property(property: "badge_color", type: "string", example: "#28A745"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "PurchaseInvoice",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "item_purchased", type: "string", example: "Office Chair"),
        new OA\Property(property: "total_price", type: "number", format: "float", example: 299.99),
        new OA\Property(property: "budget", type: "number", format: "float", example: 350.00),
        new OA\Property(property: "quantity", type: "integer", example: 5),
        new OA\Property(property: "img", type: "string", nullable: true, example: "https://example.com/image.jpg"),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "PurchaseRequest",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "request_status_id", type: "integer", example: 1),
        new OA\Property(property: "category_id", type: "integer", example: 1),
        new OA\Property(property: "invoice_id", type: "integer", nullable: true, example: 1),
        new OA\Property(property: "requested_by", type: "string", example: "John Doe"),
        new OA\Property(property: "approved_by", type: "string", nullable: true, example: "Jane Smith"),
        new OA\Property(property: "item_requested", type: "string", example: "Office Supplies"),
        new OA\Property(property: "description", type: "string", nullable: true, example: "Urgent office supplies needed"),
        new OA\Property(property: "quantity", type: "integer", example: 10),
        new OA\Property(property: "price_per_pcs", type: "number", format: "float", example: 25.50),
        new OA\Property(property: "request_date", type: "string", format: "date", example: "2024-01-15"),
        new OA\Property(property: "approved_budget", type: "number", format: "float", nullable: true, example: 500.00),
        new OA\Property(property: "used_budget", type: "number", format: "float", nullable: true, example: 255.00),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")
    ]
)]
#[OA\Schema(
    schema: "ItemBorrowed",
    type: "object",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "user_id", type: "integer", example: 1),
        new OA\Property(property: "item_id", type: "integer", example: 1),
        new OA\Property(property: "borrow_status_id", type: "integer", example: 1),
        new OA\Property(property: "quantity", type: "integer", example: 2),
        new OA\Property(property: "borrow_date", type: "string", format: "date", example: "2024-01-15"),
        new OA\Property(property: "return_date", type: "string", format: "date", nullable: true, example: "2024-01-25"),
        new OA\Property(property: "due_date", type: "string", format: "date", example: "2024-01-22"),
        new OA\Property(property: "fine", type: "number", format: "float", example: 15.00),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(property: "updated_at", type: "string", format: "date-time")    ]
)]
#[OA\Schema(
    schema: "ValidationError",
    type: "object",
    properties: [
        new OA\Property(property: "message", type: "string", example: "The given data was invalid."),
        new OA\Property(
            property: "errors",
            type: "object",
            additionalProperties: new OA\AdditionalProperties(
                type: "array",
                items: new OA\Items(type: "string")
            ),
            example: [
                "name" => ["The name field is required."],
                "email" => ["The email field must be a valid email address."]
            ]
        )
    ]
)]
#[OA\Schema(
    schema: "PurchaseInvoiceStoreRequest",
    type: "object",
    required: ["item_purchased", "total_price", "budget", "quantity"],
    properties: [
        new OA\Property(property: "item_purchased", type: "string", maxLength: 255, example: "Office Laptop"),
        new OA\Property(property: "total_price", type: "number", format: "float", minimum: 0, example: 1200.00),
        new OA\Property(property: "budget", type: "number", format: "float", minimum: 0, example: 1500.00),
        new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 3),
        new OA\Property(property: "img", type: "string", nullable: true, example: "laptop-image.jpg")
    ]
)]
#[OA\Schema(
    schema: "PurchaseInvoiceUpdateRequest",
    type: "object",
    properties: [
        new OA\Property(property: "item_purchased", type: "string", maxLength: 255, example: "Office Laptop"),
        new OA\Property(property: "total_price", type: "number", format: "float", minimum: 0, example: 1200.00),
        new OA\Property(property: "budget", type: "number", format: "float", minimum: 0, example: 1500.00),
        new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 3),
        new OA\Property(property: "img", type: "string", nullable: true, example: "laptop-image.jpg")
    ]
)]
#[OA\Schema(
    schema: "RequestStatusStoreRequest",
    type: "object",
    required: ["status_name", "badge_color"],
    properties: [
        new OA\Property(property: "status_name", type: "string", maxLength: 255, example: "Approved"),
        new OA\Property(property: "badge_color", type: "string", maxLength: 255, example: "green")
    ]
)]
#[OA\Schema(
    schema: "RequestStatusUpdateRequest",
    type: "object",
    properties: [
        new OA\Property(property: "status_name", type: "string", maxLength: 255, example: "Approved"),
        new OA\Property(property: "badge_color", type: "string", maxLength: 255, example: "green")    ]
)]
#[OA\Schema(
    schema: "PurchaseRequestStoreRequest",
    type: "object",
    required: ["item_requested", "quantity", "price_per_pcs", "request_date"],
    properties: [
        new OA\Property(property: "item_requested", type: "string", maxLength: 255, example: "Office Supplies"),
        new OA\Property(property: "description", type: "string", nullable: true, example: "Urgent office supplies needed"),
        new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 10),
        new OA\Property(property: "price_per_pcs", type: "number", format: "float", minimum: 0, example: 25.50),
        new OA\Property(property: "request_date", type: "string", format: "date", example: "2024-01-15"),
        new OA\Property(property: "approved_budget", type: "number", format: "float", nullable: true, minimum: 0, example: 500.00),
        new OA\Property(property: "used_budget", type: "number", format: "float", nullable: true, minimum: 0, example: 255.00)
    ]
)]
#[OA\Schema(
    schema: "PurchaseRequestUpdateRequest",
    type: "object",
    properties: [
        new OA\Property(property: "item_requested", type: "string", maxLength: 255, example: "Office Supplies"),
        new OA\Property(property: "description", type: "string", nullable: true, example: "Urgent office supplies needed"),
        new OA\Property(property: "quantity", type: "integer", minimum: 1, example: 10),
        new OA\Property(property: "price_per_pcs", type: "number", format: "float", minimum: 0, example: 25.50),
        new OA\Property(property: "request_date", type: "string", format: "date", example: "2024-01-15"),
        new OA\Property(property: "approved_budget", type: "number", format: "float", nullable: true, minimum: 0, example: 500.00),
        new OA\Property(property: "used_budget", type: "number", format: "float", nullable: true, minimum: 0, example: 255.00)
    ]
)]
class OpenApiController extends Controller
{
    // This class is just for OpenAPI documentation
    // It doesn't contain actual controller methods
}
