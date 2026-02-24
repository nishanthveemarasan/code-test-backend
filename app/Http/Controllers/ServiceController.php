<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use App\Services\MyAreaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    
    public function __construct(protected MyAreaService $service) {}

    public function store(StoreServiceRequest $request)
    {
        Gate::authorize('create', Service::class);
        try {
            $this->service->store(auth()->user(), $request->validated());
            return ApiResponse::success("Service created successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Create Service Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to create Service");
        }
    }

    public function update(StoreServiceRequest $request, Service $service)
    {
        Gate::authorize('update', $service);
        try {
            $this->service->update($service, $request->validated());
            return ApiResponse::success("Service updated successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Update Service Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Service");
        }
    }

    public function delete(Service $service)
    {
        Gate::authorize('delete', $service);
        try {
            $this->service->delete($service);
            return ApiResponse::success("Service deleted successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Delete Service Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to delete Service");
        }
    }
}
