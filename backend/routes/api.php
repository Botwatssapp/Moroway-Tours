<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{PublicController,AdminController,AuthController};
Route::get('tours',[PublicController::class,'tours']);
Route::get('tours/{slug}',[PublicController::class,'tour']);
Route::get('destinations',[PublicController::class,'destinations']);
Route::get('reviews',[PublicController::class,'reviews']);
Route::get('settings',[PublicController::class,'settings']);
Route::post('bookings',[PublicController::class,'booking'])->middleware('throttle:submissions');
Route::post('contacts',[PublicController::class,'contact'])->middleware('throttle:submissions');
Route::middleware(['auth:sanctum','admin'])->prefix('admin')->group(function(){
 Route::get('me',fn(\Illuminate\Http\Request $r)=>$r->user());
 Route::get('stats',[AdminController::class,'stats']);
 Route::put('settings',[AdminController::class,'settings']);
 Route::post('tours/{tour}/images',[AdminController::class,'upload']);
 Route::delete('images/{image}',[AdminController::class,'deleteImage']);
 foreach(['tours','destinations','packages','availabilities','bookings','contacts','reviews'] as $resource){
  Route::get($resource,[AdminController::class,'index'])->defaults('resource',$resource);
  if(!in_array($resource,['bookings','contacts']))Route::post($resource,[AdminController::class,'store'])->defaults('resource',$resource);
  Route::put($resource.'/{id}',[AdminController::class,'update'])->whereNumber('id')->defaults('resource',$resource);
  Route::delete($resource.'/{id}',[AdminController::class,'destroy'])->whereNumber('id')->defaults('resource',$resource);
 }
});
