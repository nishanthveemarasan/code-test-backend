<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct(protected ProjectService $service) {}

    public function save(StoreProjectRequest $request, ?Project $project = null)
    {
       if ($project) {
            Gate::authorize('update', $project);
        } else {
            Gate::authorize('create', Project::class);
        }

        try {
            DB::beginTransaction();
            $this->service->storeOrUpdate($request->validated(), auth()->user(), $project);
            DB::commit();
            return ApiResponse::success("Project saved successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('exception')->error('Update Project Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Project");
        }
    }

    public function delete(Project $project)
    {
        Gate::authorize('delete', $project);
        try {
            $this->service->delete($project);
            return ApiResponse::success("Project deleted successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Delete Project Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to delete Project");
        }
    }
}
