<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveContactInfoRequest;
use App\Http\Requests\StoreExperienceRequest;
use App\Models\Experience;
use App\Services\ExperienceService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ExperienceController extends Controller
{
    public function __construct(protected ExperienceService $service) {}

    
    /**
     * Method storeExperience
     *
     * @param StoreExperienceRequest $request [explicite description]
     *
     * @return void
     */
    public function storeExperience(StoreExperienceRequest $request)
    {
       Gate::authorize('create', Experience::class);
        try {
            $experience = $this->service->createExperience(
                $request->validated(),
                auth()->user()
            );

            return ApiResponse::success("Experience created successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Registration failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to create Experience");
        }
    }
    
    /**
     * Method updateExperience
     *
     * @param StoreExperienceRequest $request [explicite description]
     * @param Experience $experience [explicite description]
     *
     * @return void
     */
    public function updateExperience(StoreExperienceRequest $request, Experience $experience)
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
            Log::channel('exception')->error('Registration failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Experience");
        }
    }
    
    /**
     * Method deleteExperience
     *
     * @param Experience $experience [explicite description]
     *
     * @return void
     */
    public function deleteExperience(Experience $experience)
    {
        Gate::authorize('delete', $experience);
        try {
            $this->service->deleteExperience(
                auth()->user(),
                $experience
            );

            return ApiResponse::success("Experience deleted successfully!");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Registration failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Experience");
        }
    }
}
