<?php

namespace App\Services;

use App\Http\Resources\HomePageResource;
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
            $services = Service::select('title', 'description')->get();
            $projects = Project::select('id', 'name', 'type', 'city')->get();
            $mainPageContent = MainPage::with(['files:id,fileable_id,title,path,mime_type'])->where('user_id', config('admin.owner.id'))->first();
            return [
                'services' => $services->toResourceCollection(),
                'content' => $mainPageContent->toResource(),
                'projects' => $projects->toResourceCollection()
            ];
        });
    }

    public function servicesPageData()
    {
        $services = Service::select('title', 'special_point', 'points', 'description')->get();
        return $services->toResourceCollection();
    }

    public function testimonialPageData()
    {
        $testimonials = Testimonial::select('first_name', 'last_name', 'star', 'title', 'content')->get();
        return $testimonials->toResourceCollection();
    }

    public function contactUsPage()
    {
        $contact = Profile::select('email', 'phone', 'address')
                    ->where('user_id',config('admin.owner.id'))->first();
        return $contact->toResource();
    }

    public function AboutPage()
    {

        return Cache::remember('about_page_data', now()->addHours(6), function () {
            $services = Service::select('title', 'special_point')->get();
            $experiences = Experience::select('company', 'role', 'from', 'to', 'description')->get();
            $educations = Education::select('course', 'from', 'to', 'institution', 'description')->get();
            $skills = Skill::select('name')->get();
            $profile = Profile::select('first_name', 'last_name', 'id', 'biography', 'bottom_line')->first();
            return [
                'services' => $services->toResourceCollection(),
                'experiences' => $experiences->toResourceCollection(),
                'educations' => $educations->toResourceCollection(),
                'skills' => $skills->toResourceCollection(),
                'profile' => $profile->toResource(),
            ];
        });
    }
}
