<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Requests\StoreTestimonialRequest;
use App\Models\Testimonial;
use App\Services\TestimonialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TestimonialController extends Controller
{
    public function __construct(protected TestimonialService $service) {}

    public function store(StoreTestimonialRequest $request)
    {
        Gate::authorize('create', Testimonial::class);
        try {
            $testimonial = $this->service->store($request->validated(), auth()->user());
            return ApiResponse::success("Testimonial created successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Create Testimonial Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to create Testimonial");
        }
    }

    public function update(StoreTestimonialRequest $request, Testimonial $testimonial)
    {
        Gate::authorize('update', $testimonial);
        try {
            $testimonial = $this->service->update($testimonial, $request->validated());
            return ApiResponse::success("Testimonial updated successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Update Testimonial Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to update Testimonial");
        }
    }

    public function delete(Testimonial $testimonial)
    {
        Gate::authorize('delete', $testimonial);
        try {
            $this->service->delete($testimonial);
            return ApiResponse::success("Testimonial deleted successfully");
        } catch (\Exception $e) {
            Log::channel('exception')->error('Delete Testimonial Failed: ' . $e->getMessage() . ' in file: ' . $e->getFile() . ' on line: ' . $e->getLine());
            return ApiResponse::error("Failed to delete Testimonial");
        }
    }
}
