<?php
// Additive data update, no schema migration and no deletion of bookings or historical offers.
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
if(PHP_SAPI!=='cli')exit(1);
$before=DB::table('bookings')->orderBy('id')->get()->toJson();$count=DB::table('tours')->count();
(new Database\Seeders\CityCatalogueSeeder)->run();
if($before!==DB::table('bookings')->orderBy('id')->get()->toJson())throw new RuntimeException('Booking data changed unexpectedly.');
echo json_encode(['historical_tours_retained'=>$count,'total_tours'=>DB::table('tours')->count(),'published_tours'=>DB::table('tours')->where('published',true)->count(),'bookings_unchanged'=>true,'bookings'=>DB::table('bookings')->count()],JSON_PRETTY_PRINT).PHP_EOL;
