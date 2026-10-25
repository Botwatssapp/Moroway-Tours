<?php
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if(PHP_SAPI!=='cli')exit(1);
$before=\App\Models\Booking::orderBy('id')->get()->toJson();
(new \Database\Seeders\VerifiedPhotoSeeder)->run();
if($before!==\App\Models\Booking::orderBy('id')->get()->toJson())throw new RuntimeException('Unexpected booking change.');
echo 'Verified city photographs applied. Existing bookings unchanged.'.PHP_EOL;
