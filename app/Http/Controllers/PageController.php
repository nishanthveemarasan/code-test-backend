<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function __construct(protected PageService $service)
    {
    }

    public function homePageData(Request $request)
    {
        try {
            $response = $this->service->homePageData();
            return ApiResponse::success("Projects retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Projects");
        }
    }

    public function servicesPageData(Request $request)
    {
        try {
            $response = $this->service->servicesPageData();
            return ApiResponse::success("Projects retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Projects");
        }
    }

    public function testimonialPageData(Request $request)
    {
        try {
            $response = $this->service->testimonialPageData();
            return ApiResponse::success("Projects retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Projects");
        }
    }

    public function contactUsPage(Request $request)
    {
        try {
            $response = $this->service->contactUsPage();
            return ApiResponse::success("Projects retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Projects");
        }
    }

    public function AboutPage(Request $request)
    {
        try {
            $response = $this->service->AboutPage();
            return ApiResponse::success("Projects retrieved successfully", $response);
        } catch (\Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to retrieve Projects");
        }
    }

}
