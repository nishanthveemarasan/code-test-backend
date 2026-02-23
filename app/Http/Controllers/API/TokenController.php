<?php

namespace App\Http\Controllers\API;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TokenController extends Controller
{
    public function generateClientToken(Request $request)
    {
        return ApiResponse::success('Token generated successfully', ['access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIwMTljNjcwYy03ODMzLTcwOWQtODE0Ny1kM2JhMWQ5Y2I0NzUiLCJqdGkiOiIyODBhOTZiODVjMDZhNDZjNTNmZDU5Mjk3YTA0ZGUwNzIzYmNlMjc3N2U0ZDBmOTIwYTdlOWNlZDA4MjU1MzUxOGZiNTZiOGNlZDg0OTg4NSIsImlhdCI6MTc3MTQwODI5Mi4zNzE0MzksIm5iZiI6MTc3MTQwODI5Mi4zNzE0NDQsImV4cCI6MTgwMjk0NDI5Mi4zNjYwMDQsInN1YiI6IjAxOWM2NzBjLTc4MzMtNzA5ZC04MTQ3LWQzYmExZDljYjQ3NSIsInNjb3BlcyI6WyJjbGllbnRfYXV0aGVudGljYXRpb24iXX0.Kl26DhXVWIaEOz496IsfYS8Ec1cLNM-sN7sH-YWJ_0ofgEukIpWqyb7DCnOsSbebKDdEweS53I1uvGJYfFTJc83XjUmzcmqM5Sb85LZbSSBgNJh8BE1z8Fp-SCki2VojbytvFWPhn4eo-dBVb7bFseEsjeLhsEBqDEfqvMvAAxjxYho7UeeKKYrKW3_8RPi3PZCer2fd5uPIW8jooOEV6IRo8P_6pKttAWlJUQ75UTltCp0WjcOie-23m9cfvAuOVewyun2jTPQuGtBLvEdI1IuLU_olDoK1O7EsVWNA6e6g61ccT3-xj-joTesdW7FKfmZKr15hE6GMlRaW_WmHcLNG8mxSMHNdSUhRUhPv5Vtc46tTQNtUNrwNfPx3ExYS-IEEnfNtnJVmXr8JX3IMZ0x4KadNJSKbPqJmhnz7leP0G-2LfUXQu0oaqPYQANyK7VUA09pBue0y3K9koLk90dt0qLmvRMD7l2bC60ij4OaPU9H2nyzBLxyzazgWLy-JiThQ6VYHrGbGfjLWEe5OmJrxmvzbTFoWM2AwvE2hiXXyOv67NOIJk-xShBdgejSg1ELA_mTNI-O9K3I6qTr3rSikLICkuz9RL5UgaqRGtiCGgp-FgonBhEL3_mrt4QheQIVwBittUnkDfhzVzZ2PQ0FEE8QDpoZvOxjR9Jlll-o']);
        $clientId = config('admin.passport.client_id');
        $clientSecret = config('admin.passport.client_secret');
        
        if (!$clientId || !$clientSecret) {
            return ApiResponse::error('OAuth client credentials not configured.');
        }
        try {
            $tokenResponse = Http::asForm()->post(config('app.url').'/api/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => 'client_authentication',
            ]);
            $accessToken = $tokenResponse->json()['access_token'];
            return ApiResponse::success('Token generated successfully', ['access_token' => $accessToken]);
        } catch (\Exception $e) {
            Log::channel('exception')->error("Error generating client token: " . $e->getMessage(). " in file: " . $e->getFile() . " on line: " . $e->getLine());
            return ApiResponse::error('Failed to generate token');
        }
    }
}
