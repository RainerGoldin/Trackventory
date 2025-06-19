# 📦 Trackventory - Inventory Tracking System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/API-RESTful-green.svg" alt="API Type">
  <img src="https://img.shields.io/badge/Documentation-Swagger-orange.svg" alt="Documentation">
  <img src="https://img.shields.io/badge/Authentication-Sanctum-purple.svg" alt="Authentication">
</p>

Trackventory is a comprehensive inventory tracking system built with Laravel that provides a robust API for managing inventory items, tracking borrowed items, handling purchase requests and invoices, and managing user roles and permissions.

## 🚀 Features

### 📋 **Core Functionality**
- **Inventory Management**: Track items, categories, stock levels, and item details
- **Borrowing System**: Monitor borrowed items with due dates, return tracking, and fines
- **Purchase Management**: Handle purchase requests and invoices with budget tracking
- **User Management**: Role-based access control with authentication
- **Status Tracking**: Manage borrow statuses and request statuses

### 🔧 **API Features**
- **RESTful API**: Complete CRUD operations for all resources
- **Authentication**: Secure Bearer token authentication with Laravel Sanctum
- **Search & Filter**: Advanced search capabilities across all resources
- **Sorting**: Flexible sorting options for all list endpoints
- **Pagination**: Configurable pagination (1-100 items per page)
- **Comprehensive Documentation**: Interactive Swagger/OpenAPI documentation

### 📊 **Advanced Features**
- **Database Seeding**: Pre-populated with realistic sample data
- **Data Relationships**: Proper foreign key constraints and relationships
- **Validation**: Comprehensive input validation on all endpoints
- **Error Handling**: Standardized error responses
- **CORS Support**: Cross-origin resource sharing enabled

## 🛠️ Technology Stack

- **Backend**: Laravel 11.x
- **Database**: MySQL/SQLite
- **Authentication**: Laravel Sanctum
- **API Documentation**: L5-Swagger (OpenAPI 3.0)
- **Testing**: PHPUnit
- **Development**: PHP 8.2+, Composer

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL or SQLite
- Web server (Apache/Nginx) or use Laravel's built-in server

### 🔧 Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd Trackventory
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=trackventory
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the Development Server**
   ```bash
   php artisan serve
   ```

7. **Access the Application**
   - API Base URL: `http://127.0.0.1:8000/api`
   - Documentation: `http://127.0.0.1:8000/api/documentation`

## 🔐 Authentication

### Getting Started with Authentication

1. **Register a New User**
   ```bash
   POST /api/register
   Content-Type: application/json

   {
     "name": "John Doe",
     "email": "john@example.com",
     "password": "password123",
     "role_id": 1
   }
   ```

2. **Login**
   ```bash
   POST /api/login
   Content-Type: application/json

   {
     "email": "john@example.com",
     "password": "password123"
   }
   ```

3. **Use the Bearer Token**
   ```bash
   Authorization: Bearer {your-token-here}
   ```

### Default Seeded User
After running the seeders, you can use this admin account:
- **Email**: `admin@trackventory.com`
- **Password**: `admin123`
- **Role**: Administrator

## 📚 API Documentation

### 🌐 **Interactive Documentation**
Visit `http://127.0.0.1:8000/api/documentation` for complete interactive API documentation powered by Swagger UI.

### 📋 **Available Endpoints**

#### **Authentication**
- `POST /api/register` - Register new user
- `POST /api/login` - User login
- `POST /api/logout` - User logout (authenticated)

#### **Items Management**
- `GET /api/items` - List all items (with search, sort, pagination)
- `POST /api/items` - Create new item
- `GET /api/items/{id}` - Get specific item
- `PUT /api/items/{id}` - Update item
- `DELETE /api/items/{id}` - Delete item

#### **Categories Management**
- `GET /api/categories` - List all categories
- `POST /api/categories` - Create category
- `GET /api/categories/{id}` - Get specific category
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category

#### **Borrowed Items Tracking**
- `GET /api/item-borroweds` - List borrowed items (with search, sort, pagination)
- `POST /api/item-borroweds` - Create borrowing record
- `GET /api/item-borroweds/{id}` - Get specific borrowed item
- `PUT /api/item-borroweds/{id}` - Update borrowing record
- `DELETE /api/item-borroweds/{id}` - Delete borrowing record

#### **Purchase Management**
- `GET /api/purchase-requests` - List purchase requests (with search, sort, pagination)
- `POST /api/purchase-requests` - Create purchase request
- `GET /api/purchase-requests/{id}` - Get specific request
- `PUT /api/purchase-requests/{id}` - Update purchase request
- `DELETE /api/purchase-requests/{id}` - Delete purchase request

- `GET /api/purchase-invoices` - List purchase invoices (with search, sort, pagination)
- `POST /api/purchase-invoices` - Create purchase invoice
- `GET /api/purchase-invoices/{id}` - Get specific invoice
- `PUT /api/purchase-invoices/{id}` - Update purchase invoice
- `DELETE /api/purchase-invoices/{id}` - Delete purchase invoice

#### **Status Management**
- `GET /api/borrow-statuses` - List borrow statuses
- `POST /api/borrow-statuses` - Create borrow status
- `GET /api/borrow-statuses/{id}` - Get specific status
- `PUT /api/borrow-statuses/{id}` - Update status
- `DELETE /api/borrow-statuses/{id}` - Delete status

- `GET /api/request-statuses` - List request statuses
- `POST /api/request-statuses` - Create request status
- `GET /api/request-statuses/{id}` - Get specific status
- `PUT /api/request-statuses/{id}` - Update status
- `DELETE /api/request-statuses/{id}` - Delete status

#### **User & Role Management**
- `GET /api/roles` - List user roles
- `POST /api/roles` - Create role
- `GET /api/roles/{id}` - Get specific role
- `PUT /api/roles/{id}` - Update role
- `DELETE /api/roles/{id}` - Delete role

## 🔍 Advanced Usage

### **Search, Sort & Pagination**

#### Search Examples
```bash
# Search items by name, description, or brand
GET /api/items?search=laptop

# Search borrowed items by dates
GET /api/item-borroweds?search=2024-01-15

# Search purchase requests by item or description
GET /api/purchase-requests?search=office supplies
```

#### Sorting Examples
```bash
# Sort items by name (ascending)
GET /api/items?sort_by=item_name&sort_order=asc

# Sort by creation date (descending)
GET /api/purchase-invoices?sort_by=created_at&sort_order=desc

# Sort borrowed items by due date
GET /api/item-borroweds?sort_by=due_date&sort_order=asc
```

#### Pagination Examples
```bash
# Get 10 items per page, page 2
GET /api/items?per_page=10&page=2

# Combine search, sort, and pagination
GET /api/items?search=laptop&sort_by=stock&sort_order=desc&per_page=5&page=1
```

### **Response Format**
All list endpoints return data in this format:
```json
{
  "message": "Items retrieved successfully",
  "data": [
    {
      "id": 1,
      "item_name": "Laptop",
      "stock": 10,
      "...": "..."
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 67,
    "from": 1,
    "to": 15
  }
}
```

## 🗄️ Database Schema

### **Core Tables**
- **users** - User accounts and authentication
- **roles** - User roles (Admin, Manager, User, etc.)
- **categories** - Item categories
- **items** - Inventory items
- **borrow_statuses** - Status types for borrowed items
- **request_statuses** - Status types for requests
- **item_borroweds** - Borrowing records
- **purchase_requests** - Purchase request records
- **purchase_invoices** - Purchase invoice records

### **Relationships**
- Users belong to Roles
- Items belong to Categories
- Borrowed Items belong to Users and Items
- Borrowed Items have Borrow Status
- Purchase Requests have Request Status and Category

## 🧪 Testing

### **Run Tests**
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ItemControllerTest.php

# Run with coverage
php artisan test --coverage
```

### **API Testing**
Use the interactive Swagger documentation at `/api/documentation` to test all endpoints directly in your browser.

### **Sample API Calls**

#### Get Authentication Token
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@trackventory.com","password":"admin123"}'
```

#### Use Token to Access Protected Endpoint
```bash
curl -X GET http://127.0.0.1:8000/api/items \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

## 📁 Project Structure

```
Trackventory/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── API/           # API Controllers
│   │       └── OpenApiController.php  # OpenAPI Documentation
│   ├── Models/                # Eloquent Models
│   └── Providers/             # Service Providers
├── database/
│   ├── factories/             # Model Factories
│   ├── migrations/            # Database Migrations
│   └── seeders/               # Database Seeders
├── routes/
│   ├── api.php               # API Routes
│   └── web.php               # Web Routes
├── config/
│   └── l5-swagger.php        # Swagger Configuration
└── storage/
    └── api-docs/             # Generated Swagger Documentation
```

## 🔧 Configuration

### **Swagger Documentation**
The API documentation is automatically generated from code annotations. To regenerate:
```bash
php artisan l5-swagger:generate
```

### **CORS Configuration**
CORS is configured in `config/cors.php`. Modify as needed for your frontend application.

### **Sanctum Configuration**
Authentication settings can be modified in `config/sanctum.php`.

## 🚀 Deployment

### **Production Deployment**

1. **Environment Setup**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

3. **Generate Documentation**
   ```bash
   php artisan l5-swagger:generate
   ```

### **Environment Variables**
Key environment variables for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_DATABASE=trackventory_prod
DB_USERNAME=your-db-user
DB_PASSWORD=your-secure-password

SANCTUM_STATEFUL_DOMAINS=your-frontend-domain.com
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👥 Support

For support, email support@trackventory.com or create an issue in the GitHub repository.

## 🎯 Future Enhancements

- [ ] Real-time notifications for due dates
- [ ] Advanced reporting and analytics
- [ ] Barcode scanning integration
- [ ] Mobile application
- [ ] Advanced user permissions
- [ ] Audit logging
- [ ] Export functionality (PDF, Excel)
- [ ] Dashboard with charts and metrics

---

**Built with ❤️ using Laravel Framework**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
