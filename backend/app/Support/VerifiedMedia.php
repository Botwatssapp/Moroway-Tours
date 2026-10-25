<?php
namespace App\Support;

class VerifiedMedia {
 public static function all(): array {static $photos;$path=database_path('data/verified-photos.json');return $photos??=(is_file($path)?array_values(array_filter(json_decode(file_get_contents($path),true,512,JSON_THROW_ON_ERROR),fn($photo)=>isset($photo['path'],$photo['variants']))):[]);}
 public static function find(string $path): ?array {foreach(self::all() as $photo)if($photo['path']===$path)return $photo;return null;}
 public static function metadata(string $path): array {$photo=self::find($path);if(!$photo)return [];return ['src_set'=>$photo['src_set'],'width'=>$photo['width'],'height'=>$photo['height'],'city'=>$photo['city'],'author'=>$photo['author'],'license'=>$photo['license'],'license_url'=>$photo['license_url'],'position_css'=>$photo['position_css'],'alt_en'=>$photo['alt']['en'],'fallback'=>$photo['fallback']];}
 public static function isCityPhoto(string $path,string $city): bool {
  $photo=self::find($path);if($photo)return $photo['city']===$city;
  return $path===('/assets/destinations/'.(['Fès'=>'fes','Tanger'=>'tanger','Casablanca'=>'casablanca'][$city]??'unapproved').'.jpg');
 }
 public static function decorateTour(\App\Models\Tour $tour): \App\Models\Tour {$images=$tour->images->filter(fn($image)=>self::find($image->path));if($images->isNotEmpty())$tour->setRelation('images',$images->values());return $tour;}
}
