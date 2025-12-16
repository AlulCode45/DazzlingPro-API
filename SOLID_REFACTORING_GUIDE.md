# SOLID Architecture Refactoring Guide

This guide demonstrates how to refactor Laravel API controllers to follow SOLID principles using the Testimonial module as an example.

## Overview of SOLID Implementation

### 1. Single Responsibility Principle (SRP)
Each class has a single responsibility:
- **Controllers**: Handle HTTP requests/responses only
- **Services**: Contain business logic
- **Repositories**: Handle data access operations
- **Requests**: Handle validation
- **Resources**: Handle response formatting

### 2. Open/Closed Principle (OCP)
The system is open for extension but closed for modification through:
- Interfaces for all services and repositories
- Dependency injection allows easy swapping of implementations

### 3. Liskov Substitution Principle (LSP)
Base classes can be properly substituted by their implementations:
- `BaseRepository` and `BaseService` provide common functionality
- Specific implementations extend these bases while maintaining compatibility

### 4. Interface Segregation Principle (ISP)
Interfaces are specific to their responsibilities:
- `TestimonialRepositoryInterface` extends `BaseRepositoryInterface` but adds testimonial-specific methods
- `TestimonialServiceInterface` extends `BaseServiceInterface` but adds testimonial-specific business logic

### 5. Dependency Inversion Principle (DIP)
High-level modules depend on abstractions:
- Controllers depend on service interfaces, not implementations
- Services depend on repository interfaces, not implementations

## Directory Structure

```
app/
├── Http/
│   ├── Controllers/API/V1/          # Controllers (HTTP handling only)
│   ├── Resources/API/V1/            # API Resources (response formatting)
│   └── Requests/API/V1/             # Form Requests (validation)
├── Repositories/
│   ├── Contracts/                   # Repository interfaces
│   └── Eloquent/                    # Eloquent implementations
├── Services/
│   ├── Contracts/                   # Service interfaces
│   └── Implementations/             # Service implementations
└── Providers/                       # Service providers for DI
```

## Refactoring Pattern for Other Controllers

### Step 1: Create Repository Interface

Create `app/Repositories/Contracts/{ModelName}RepositoryInterface.php`:

```php
<?php

namespace App\Repositories\Contracts;

use App\Models{ModelName};

interface {ModelName}RepositoryInterface extends BaseRepositoryInterface
{
    // Add model-specific query methods
    public function findByCustomField($value): Collection;
    public function getActiveRecords(): Collection;
}
```

### Step 2: Create Repository Implementation

Create `app/Repositories/Eloquent/{ModelName}Repository.php`:

```php
<?php

namespace App\Repositories\Eloquent;

use App\Models{ModelName};
use App\Repositories\Contracts{ModelName}RepositoryInterface;

class {ModelName}Repository extends BaseRepository implements {ModelName}RepositoryInterface
{
    public function __construct({ModelName} $model)
    {
        parent::__construct($model);
    }

    public function findByCustomField($value): Collection
    {
        return $this->model->where('custom_field', $value)->get();
    }

    public function getActiveRecords(): Collection
    {
        return $this->model->where('status', true)->get();
    }
}
```

### Step 3: Create Service Interface

Create `app/Services/Contracts/{ModelName}ServiceInterface.php`:

```php
<?php

namespace App\Services\Contracts;

use App\Models{ModelName};

interface {ModelName}ServiceInterface extends BaseServiceInterface
{
    // Add business logic methods
    public function activate(int $id): {ModelName};
    public function deactivate(int $id): {ModelName};
    public function getStatistics(): array;
}
```

### Step 4: Create Service Implementation

Create `app/Services/Implementations/{ModelName}Service.php`:

```php
<?php

namespace App\Services\Implementations;

use App\Services\Contracts{ModelName}ServiceInterface;
use App\Repositories\Contracts{ModelName}RepositoryInterface;
use App\Models{ModelName};
use Illuminate\Support\Facades\Cache;

class {ModelName}Service extends BaseService implements {ModelName}ServiceInterface
{
    private {ModelName}RepositoryInterface $repository;

    public function __construct({ModelName}RepositoryInterface $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }

    public function activate(int $id): {ModelName}
    {
        $model = $this->repository->findOrFail($id);
        $model->update(['status' => true]);

        // Clear cache if needed
        Cache::forget('active_{model_name_lower}');

        return $model;
    }

    public function deactivate(int $id): {ModelName}
    {
        $model = $this->repository->findOrFail($id);
        $model->update(['status' => false]);

        // Clear cache if needed
        Cache::forget('active_{model_name_lower}');

        return $model;
    }

    public function getStatistics(): array
    {
        return Cache::remember('{model_name_lower}_statistics', 3600, function () {
            $models = $this->repository->all();

            return [
                'total' => $models->count(),
                'active' => $models->where('status', true)->count(),
                'inactive' => $models->where('status', false)->count(),
            ];
        });
    }
}
```

### Step 5: Create Form Request Classes

Create `app/Http/Requests/API/V1/Store{ModelName}Request.php`:

```php
<?php

namespace App\Http\Requests\API\V1;

class Store{ModelName}Request extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Define validation rules
            'field1' => 'required|string|max:255',
            'field2' => 'nullable|integer',
            'field3' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            // Define custom error messages
            'field1.required' => 'Field 1 is required.',
            'field2.integer' => 'Field 2 must be an integer.',
        ];
    }
}
```

Create `app/Http/Requests/API/V1/Update{ModelName}Request.php`:

```php
<?php

namespace App\Http\Requests\API\V1;

class Update{ModelName}Request extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'field1' => 'sometimes|required|string|max:255',
            'field2' => 'nullable|integer',
            'field3' => 'sometimes|boolean',
        ];
    }
}
```

### Step 6: Create API Resource Classes

Create `app/Http/Resources/API/V1/{ModelName}Resource.php`:

```php
<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;

class {ModelName}Resource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'field1' => $this->field1,
            'field2' => $this->field2,
            'field3' => $this->field3,
            // Add other fields
        ] + $this->formatTimestamps();
    }
}
```

Create `app/Http/Resources/API/V1/{ModelName}Collection.php`:

```php
<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class {ModelName}Collection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => {ModelName}Resource::collection($this->collection),
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total(),
            ],
        ];
    }
}
```

### Step 7: Refactor Controller

Update `app/Http/Controllers/API/V1/{ModelName}Controller.php`:

```php
<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Store{ModelName}Request;
use App\Http\Requests\API\V1\Update{ModelName}Request;
use App\Http\Resources\API\V1{ModelName}Resource;
use App\Http\Resources\API\V1{ModelName}Collection;
use App\Services\Contracts{ModelName}ServiceInterface;
use Illuminate\Http\JsonResponse;

class {ModelName}Controller extends Controller
{
    private {ModelName}ServiceInterface $service;

    public function __construct({ModelName}ServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $models = $this->service->paginate();

        return $this->sendResponseWithPagination(
            new {ModelName}Collection($models),
            '{ModelNames} retrieved successfully.'
        );
    }

    public function store(Store{ModelName}Request $request): JsonResponse
    {
        $model = $this->service->create($request->validated());

        return $this->sendResponse(
            new {ModelName}Resource($model),
            '{ModelName} created successfully.',
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $model = $this->service->getByIdOrFail($id);

        return $this->sendResponse(
            new {ModelName}Resource($model),
            '{ModelName} retrieved successfully.'
        );
    }

    public function update(Update{ModelName}Request $request, int $id): JsonResponse
    {
        $model = $this->service->update($id, $request->validated());

        return $this->sendResponse(
            new {ModelName}Resource($model),
            '{ModelName} updated successfully.'
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);

        return $this->sendResponse(
            [],
            '{ModelName} deleted successfully.'
        );
    }

    // Add custom endpoints as needed
    public function statistics(): JsonResponse
    {
        $statistics = $this->service->getStatistics();

        return $this->sendResponse(
            $statistics,
            '{ModelName} statistics retrieved successfully.'
        );
    }
}
```

### Step 8: Register Dependencies

Update `app/Providers/RepositoryServiceProvider.php`:

```php
<?php

namespace App\Providers;

use App\Repositories\Contracts{ModelName}RepositoryInterface;
use App\Repositories\Eloquent{ModelName}Repository;
use App\Services\Contracts{ModelName}ServiceInterface;
use App\Services\Implementations{ModelName}Service;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Add your bindings
        $this->app->singleton({ModelName}RepositoryInterface::class, function ($app) {
            return new {ModelName}Repository($app->make('App\Models{ModelName}'));
        });

        $this->app->singleton({ModelName}ServiceInterface::class, function ($app) {
            return new {ModelName}Service($app->make({ModelName}RepositoryInterface::class));
        });
    }
}
```

## Benefits of This Architecture

1. **Testability**: Easy to unit test each layer independently
2. **Maintainability**: Clear separation of concerns makes code easier to maintain
3. **Extensibility**: Easy to add new features without modifying existing code
4. **Flexibility**: Can swap implementations (e.g., switch from Eloquent to MongoDB)
5. **Reusability**: Services and repositories can be reused across different controllers
6. **Single Responsibility**: Each class has one clear purpose

## Migration Strategy

1. Start with one controller (as shown with Testimonial)
2. Create the interface and implementation files
3. Update the controller to use the new architecture
4. Test thoroughly
5. Move to the next controller
6. Repeat until all controllers are refactored

This approach ensures a smooth migration with minimal disruption to the existing API.