<?php
namespace App\Services;

use App\Models\Testimonial;
use App\Models\User;


class TestimonialService
{

    public function list(User $user)
    {
        $testimonials = $user->testimonials()->paginate(10);
        return $testimonials->toResourceCollection()->response()->getData(true);
    }
    public function store(array $data, User $user): array
    {
        $model = $user->testimonials()->create($data);
        return ['uuid' => $model->uuid];
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($data);
        return $testimonial;
    }

    public function delete(Testimonial $testimonial): void
    {
        $testimonial->delete();
    }
}