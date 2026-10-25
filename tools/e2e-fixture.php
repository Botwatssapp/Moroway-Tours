<?php
// CLI-only helper for disposable local browser-test records; never served by Laravel.
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
if(!$app->environment('local','testing'))throw new RuntimeException('Local tests only.');
$email=$argv[2]??'';if(!preg_match('/^e2e-[a-f0-9-]+@example\.test$/',$email))throw new RuntimeException('Invalid fixture identifier.');
if(($argv[1]??'')==='create'){
 $password=bin2hex(random_bytes(18));$user=new App\Models\User(['name'=>'Temporary browser test','email'=>$email,'password'=>$password]);$user->is_admin=true;$user->save();echo json_encode(['email'=>$email,'password'=>$password]);
}elseif(($argv[1]??'')==='cleanup'){
 App\Models\Booking::where('email',$email)->delete();App\Models\Contact::where('email',$email)->delete();App\Models\User::where('email',$email)->where('name','Temporary browser test')->delete();echo 'Fixture removed';
}elseif(($argv[1]??'')==='counts')echo json_encode(['bookings'=>App\Models\Booking::where('email',$email)->count(),'contacts'=>App\Models\Contact::where('email',$email)->count()]);
