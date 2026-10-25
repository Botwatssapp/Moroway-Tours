<?php
// Read-only SQL media inventory. No customer data or credentials are emitted.
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
$root=dirname(__DIR__);
$references=[];
foreach(['destinations'=>'image','tour_images'=>'path'] as $table=>$column){
 foreach(DB::table($table)->whereNotNull($column)->pluck($column) as $value){
  $references[(string)$value]=true;
 }
}
$media=['unique_sql_media'=>count($references),'local_sql_media'=>[],'archived_sql_media'=>[],'remote_sql_media_count'=>0,'unresolved_sql_media'=>[]];
foreach(array_keys($references) as $value){
 if(preg_match('#^https?://#i',$value)){$media['remote_sql_media_count']++;continue;}
 $candidate=null;
 if(str_starts_with($value,'/assets/'))$candidate=$root.'/frontend/public'.$value;
 elseif(str_starts_with($value,'/storage/'))$candidate=$root.'/backend/storage/app/public/'.substr($value,9);
 if($candidate&&is_file($candidate))$media['local_sql_media'][]=$value;
 elseif(str_starts_with($value,'/assets/')&&is_file($root.'/backend/storage/app/private/archived-media'.$value))$media['archived_sql_media'][]=$value;
 else $media['unresolved_sql_media'][]=$value;
}
$photoPaths=[];
foreach(App\Support\VerifiedMedia::all() as $photo)foreach($photo['variants'] as $variant){
 $file=$root.'/frontend/public'.$variant['src'];
 if(!is_file($file))throw new RuntimeException('Missing approved variant: '.$variant['src']);
 $photoPaths[$variant['src']]=true;
}
$media['verified_variants_present']=count($photoPaths);
$media['policy']='Preserve SQL-referenced media, originals and fallbacks. Historical media are stored privately under backend/storage/app/private/archived-media.';
echo json_encode($media,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).PHP_EOL;
