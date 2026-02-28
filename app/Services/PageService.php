<?php

namespace App\Services;

use App\Models\Education;
use App\Models\Experience;
use App\Models\MainPage;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class PageService
{
    public function homePageData()
    {
        return Cache::remember('home_page_data', now()->addHours(6), function () {
            $user = User::with([
                'services:user_id,title,description',
                'projects' => function ($query) {
                    $query->select('id', 'user_id', 'name', 'type', 'city')
                        ->with(['file:id,fileable_id,fileable_type,path,mime_type']);
                },

                'mainPage' => function ($query) {
                    $query->select('id', 'user_id', 'title', 'description') // Add columns you need for MainPage
                        ->with('files:id,fileable_id,title,path,mime_type');
                }
            ])->find(config('admin.owner.id'));
            return [
                'services' => $user->services->toResourceCollection(),
                'content' => $user->mainPage->toResource(),
                'projects' => $user->projects->toResourceCollection(),
                'year_of_experience' => $this->getExperinceYear($user)
            ];
        });
    }

    public function servicesPageData()
    {
        $user = User::with('services:user_id,title,special_point,points,description')
            ->find(config('admin.owner.id'));
        return [
            'services' => $user->services->toResourceCollection(),
            'year_of_experience' => $this->getExperinceYear($user)
        ];
    }

    public function testimonialPageData()
    {
        $user = User::with('testimonials:user_id,first_name,last_name,star,title,content')->find(config('admin.owner.id'));
        return $user->testimonials->toResourceCollection();
    }

    public function contactUsPage()
    {
        $user = User::with(['profile:user_id,email,phone,address'])->find(config('admin.owner.id'));
        return $user->profile->toResource();
    }

    public function AboutPage()
    {
        return Cache::remember('about_page_data', now()->addHours(6), function () {
            $user = User::with([
                'services:user_id,title,special_point',
                'experiences:user_id,company,role,from,to,description',
                'educations:user_id,course,from,to,institution,description',
                'skills:user_id,name',
                'profile:user_id,first_name,last_name,id,biography,bottom_line'
            ])->find(config('admin.owner.id'));
            return [
                'services' => $user->services->toResourceCollection(),
                'experiences' => $user->experiences->toResourceCollection(),
                'educations' => $user->educations->toResourceCollection(),
                'skills' => $user->skills->toResourceCollection(),
                'profile' => $user->profile->toResource(),
                'year_of_experience' => $this->getExperinceYear($user)
            ];
        });
    }

    private function getExperinceYear(User $user)
    {
        return now()->year - $user->firstExperience->from;
    }
}
