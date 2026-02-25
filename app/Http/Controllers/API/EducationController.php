<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEducationRequest;
use App\Models\Education;
use App\Services\EducationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EducationController extends Controller
{
    public function __construct(protected EducationService $service){}

    public function list()
    {
        Gate::authorize('viewAny', Education::class);
        try {
            $response = $this->service->list(auth()->user());
            return ApiResponse::success("Education list retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('List Education Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Education list");
        }
    }
    public function store(StoreEducationRequest $request)
    {
        Gate::authorize('create', Education::class);
        try {
            $this->service->store(
                $request->validated(),
                auth()->user()
            );

            return ApiResponse::success("Education created successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Create Education Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to create Education");
        }
    }

    public function update(StoreEducationRequest $request, Education $education)
    {
        Gate::authorize('update', $education);
        try {
            $this->service->update(
                $request->validated(),
                $education
            );
            return ApiResponse::success("Education updated successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Update Education Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Education");
        }
    }

    public function destroy(Education $education)
    {
        Gate::authorize('delete', $education);
        try {
            $this->service->delete(
                $education
            );

            return ApiResponse::success("Education deleted successfully!");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Delete Education Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to delete Education");
        }
    }
   
}
