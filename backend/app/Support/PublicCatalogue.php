<?php
namespace App\Support;
use App\Models\Tour;

class PublicCatalogue {
 public const CITIES=['Fès','Tanger','Casablanca'];
 public const MARKER='fes-tanger-casablanca';
 public static function excludedPlaces(): array {
  static $places;
  if($places!==null)return $places;
  $data=json_decode(file_get_contents(database_path('data/legacy.json')),true);
  $places=['Sahara','Atlas','Haut Atlas','High Atlas','Merzouga','desert','désert'];
  foreach($data['offers'] as $tour)foreach(array_merge([$tour['dest']],$tour['route']['places']??[],[$tour['route']['start'],$tour['route']['end']]) as $place)if(!in_array($place,self::CITIES,true))$places[]=$place;
  return $places=array_values(array_unique($places));
 }
 public static function hasExcludedContent(mixed $value): bool {
  $text=is_string($value)?$value:json_encode($value,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  foreach(self::excludedPlaces() as $place)if(preg_match('/(?<![\pL])'.preg_quote($place,'/').'(?![\pL])/iu',$text))return true;
  return false;
 }
 public static function allows(Tour $tour): bool {
  if(!$tour->published||($tour->content['region_scope']??null)!==self::MARKER||!in_array($tour->start_city,self::CITIES,true)||!in_array($tour->end_city,self::CITIES,true))return false;
  $places=$tour->content['route']['places']??[];
  if(!$places||array_diff($places,self::CITIES))return false;
  if(self::hasExcludedContent([$tour->title,$tour->description,$tour->content]))return false;
  if(!$tour->destination||self::hasExcludedContent($tour->destination->toArray()))return false;
  foreach($tour->images as $image)if(!VerifiedMedia::isCityPhoto($image->path,$tour->destination->name)||self::hasExcludedContent([$image->alt,$image->credit]))return false;
  foreach($tour->packages as $package)if(self::hasExcludedContent([$package->name,$package->description]))return false;
  return true;
 }
}
