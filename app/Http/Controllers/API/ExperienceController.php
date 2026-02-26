<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExperienceRequest;
use App\Models\Experience;
use App\Services\ExperienceService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ExperienceController extends Controller
{
    public function __construct(protected ExperienceService $service) {}

    
    public function index()
    {
        Gate::authorize('viewAny', Experience::class);
        try {
            $experiences = $this->service->list(auth()->user());
            return ApiResponse::success("Experiences retrieved successfully", $experiences);
        } catch (\Exception $e) {
            Log::channel('exception')->error('List Experiences Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Experiences");
        }
    }

    public function show(Experience $experience)
    {
        Gate::authorize('view', $experience);
        try {
            return ApiResponse::success("Experience retrieved successfully", $experience->toResource());
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Experience Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Experience");
        }
    }
    /**
     * Method store
     *
     * @param StoreExperienceRequest $request
     *
     * @return void
     */
    public function store(StoreExperienceRequest $request)
    {
       Gate::authorize('create', Experience::class);
       try {
            $response = $this->service->createExperience(
                $request->validated(),
                auth()->user()
            );

            return ApiResponse::success("Experience created successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Create Experience Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to create Experience");
        }
    }
    
    /**
     * Method update
     *
     * @param StoreExperienceRequest $request
     * @param Experience $experience
     *
     * @return void
     */
    public function update(StoreExperienceRequest $request, Experience $experience)
    {
        Gate::authorize('update', $experience);
        try {
            $experience = $this->service->updateExperience(
                $request->validated(),
                auth()->user(),
                $experience
            );
            return ApiResponse::success("Experience updated successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Update Experience Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Experience");
        }
    }
    
    /**
     * Method delete
     *
     * @param Experience $experience
     *
     * @return void
     */
    public function destroy(Experience $experience)
    {
        Gate::authorize('delete', $experience);
        try {
            $this->service->deleteExperience(
                auth()->user(),
                $experience
            );

            return ApiResponse::success("Experience deleted successfully!");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Delete Experience Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to delete Experience");
        }
    }
}
