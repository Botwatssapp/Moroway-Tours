<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder {
 public function run(): void {$this->call(CityCatalogueSeeder::class);if(\App\Support\VerifiedMedia::all())$this->call(VerifiedPhotoSeeder::class);}
}
