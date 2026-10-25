<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
Route::get('/',fn()=>response()->json(['service'=>'MOROWAY TOURS API']));
Route::post('/login',[AuthController::class,'login'])->middleware('throttle:login');
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/sitemap.xml',function(){
 $base=rtrim(config('app.frontend_url'),'/');$paths=['/','/about','/destinations','/tours','/services','/gallery','/contact','/custom','/students','/faq'];
 foreach(\App\Models\Tour::publicCatalogue()->get()->filter(fn($tour)=>\App\Support\PublicCatalogue::allows($tour))->pluck('slug') as $slug)$paths[]='/tours/'.$slug;
 $xml='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';foreach($paths as $path)$xml.='<url><loc>'.htmlspecialchars($base.$path,ENT_XML1).'</loc></url>';return response($xml.'</urlset>',200,['Content-Type'=>'application/xml']);
});
