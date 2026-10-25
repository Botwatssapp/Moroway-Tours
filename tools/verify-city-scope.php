<?php
// Read-only integration checks; no schema changes, test rows or write transaction.
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Support\{PublicCatalogue,VerifiedMedia};
$checks=0;
function verify($condition,$message){global $checks;if(!$condition)throw new RuntimeException($message);$checks++;}
function readJson($url){global $app;$request=Request::create($url,'GET',[],[],[],['HTTP_ACCEPT'=>'application/json']);$response=$app->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);return [$response->getStatusCode(),json_decode($response->getContent(),true),$response->getContent()];}
$before=DB::table('bookings')->orderBy('id')->get()->toJson();
[$status,$rows]=readJson('/api/tours');verify($status===200,'Tours API failed.');verify(count($rows)===3,'Expected three public tours.');
foreach($rows as $row){verify(in_array($row['start_city'],PublicCatalogue::CITIES,true),'Invalid departure.');verify(in_array($row['end_city'],PublicCatalogue::CITIES,true),'Invalid arrival.');verify(!PublicCatalogue::hasExcludedContent($row),'Excluded content leaked.');verify(count($row['images'])===3,'Expected three approved photos.');foreach($row['images'] as $image){verify(VerifiedMedia::isCityPhoto($image['path'],$row['start_city']),'Photo city mismatch.');verify(is_file(dirname(base_path()).'/frontend/public'.$image['path']),'Photo file missing.');verify(!empty($image['src_set'])&&!empty($image['license_url']),'Missing responsive or licence metadata.');}}
foreach(PublicCatalogue::CITIES as $city){[$status,$rows]=readJson('/api/tours?destination='.urlencode($city));verify($status===200&&count($rows)===1,'Destination filter failed.');[$status,$rows]=readJson('/api/tours?city='.urlencode($city));verify($status===200&&count($rows)===1,'Departure filter failed.');}
foreach(['Marrakech','Merzouga','Rabat','Sahara'] as $city)foreach(['city','destination','q'] as $key){[$status,$rows]=readJson('/api/tours?'.$key.'='.urlencode($city));verify($status===200&&$rows===[],'Excluded search/filter returned an offer.');}
[$status,$rows]=readJson('/api/destinations');verify($status===200&&array_column($rows,'name')===PublicCatalogue::CITIES,'Invalid public destinations.');
$old=Tour::where('slug','marrakech-fes-3')->first();if($old){verify(!$old->published,'Historical offer should be archived.');[$status]=readJson('/api/tours/'.$old->slug);verify($status===404,'Archived detail remains public.');}
[$status,$rows]=readJson('/api/reviews');verify($status===200&&!PublicCatalogue::hasExcludedContent($rows),'Review leak.');[$status,$rows]=readJson('/api/settings');verify($status===200&&!PublicCatalogue::hasExcludedContent($rows),'Settings leak.');
[$status,,$xml]=readJson('/sitemap.xml');verify($status===200&&str_contains($xml,'/tours/fes-decouverte')&&!str_contains($xml,'marrakech-fes-3'),'Sitemap leak.');
verify($before===DB::table('bookings')->orderBy('id')->get()->toJson(),'Bookings unexpectedly changed.');
echo "Read-only integration checks passed: $checks. Existing bookings unchanged.\n";
