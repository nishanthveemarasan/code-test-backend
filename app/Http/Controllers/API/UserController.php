<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMainPageRequest;
use App\Http\Requests\StoreProfileRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    /**
     * Method store
     *
     * @param StoreProfileRequest $request [explicite description]
     *
     * @return void
     */
    public function store(StoreProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $response = $this->userService->store($request->validated(), auth()->user());
            DB::commit();
            return ApiResponse::success("Profile info saved successfully", $response);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('exception')->error('Update Profile failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to save Profile info");
        }
    }

    public function storeMainPage(StoreMainPageRequest $request)
    {

        try {
            DB::beginTransaction();
            $response = $this->userService->storeMainPage($request->validated(), auth()->user());
            DB::commit();
            return ApiResponse::success("Main page info saved successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('exception')->error('Update Main Page failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to save Main page info");
        }
    }

    public function getData()
    {
        try {
            $response = $this->userService->getData(auth()->user());
            return ApiResponse::success("Main page info retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Main Page failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Main page info");
        }
    }

    public function getProfileData()
    {
        try {
            $response = $this->userService->getProfileData(auth()->user());
            return ApiResponse::success("Profile info retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Profile failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Profile info");
        }
    }
}
