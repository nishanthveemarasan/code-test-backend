<?php

namespace Database\Seeders;

use App\Models\DropboxCredential;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DropboxCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DropboxCredential::updateOrCreate(
            [
                'code' => config('admin.dropbox.store_images.code'),
            ],
            [
                'client_id' => config('admin.dropbox.store_images.client_id'),
                'client_secret' => config('admin.dropbox.store_images.client_secret'),
                'app_redirect_url' => config('admin.dropbox.store_images.app_redirect_url'),
            ]
        );
    }
}
