<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Services\DropboxService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DropboxController extends Controller
{
    public function __construct(protected DropboxService $dropboxService)
    {
       
    }
    public function authorizeDropboxApp($event)
    {
        try {
            return $this->dropboxService->authorizeDropboxApp($event);
        } catch (Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to authorize the app");
        }
    }

    public function dropboxAuthorizeCode(Request $request, $event)
    {
        try {
            return $this->dropboxService->dropboxAuthorizeCode($request, $event);
        } catch (Exception $e) {
            Log::channel('exception')->error('Get Projects Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to authorize the app");
        }
    }
}
