<?php
namespace App\Services;

use App\Models\Service;
use App\Models\User;

class MyAreaService{
    

    public function list(User $user)
    {
        $services = $user->services()->paginate(10);
        return $services->toResourceCollection()->response()->getData(true);
    }
    /**
     * Method store
     *
     * @param User $user
     * @param array $data
     *
     * @return Service
     */
    public function store(User $user, array $data): Service
    {
        return $user->services()->create($data);
    }
    
    /**
     * Method update
     *
     * @param Service $service
     * @param array $data
     *
     * @return void
     */
    public function update(Service $service, array $data): void
    {
        $service->update($data);
    }
    
    /**
     * Method delete
     *
     * @param Service $service
     *
     * @return void
     */
    public function delete(Service $service): void
    {
        $service->delete();
    }
}