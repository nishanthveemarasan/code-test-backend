<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\StoreSkillRequest;
use App\Models\Skill;
use App\Services\SkillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;


class SkillController extends Controller
{
    public function __construct(protected SkillService $service) {}
    public function store(StoreSkillRequest $request)
    {
        Gate::authorize('create', Skill::class);
        try {
            DB::beginTransaction();
            $this->service->updateSkills(
                auth()->user(),
                $request->validated()['skills']
            );
            DB::commit();
            return ApiResponse::success("Skills updated successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('exception')->error('Update Skills Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Skills");
        }
    }
}
