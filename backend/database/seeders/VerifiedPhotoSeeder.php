<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Tour,Destination};
use App\Support\VerifiedMedia;

class VerifiedPhotoSeeder extends Seeder {
 public function run(): void {DB::transaction(function(){
  foreach(['Fès'=>'fes','Tanger'=>'tanger','Casablanca'=>'casablanca'] as $city=>$slug){
   $photos=array_values(array_filter(VerifiedMedia::all(),fn($photo)=>$photo['city']===$city));
   if(!$photos)continue;
   $tour=Tour::where('slug',$slug.'-decouverte')->first();if(!$tour)continue;
   $cover=$photos[0];
   $primary=['Fès'=>'fes-bou-inania','Tanger'=>'tanger-seaside','Casablanca'=>'casablanca-hassan-ii'][$city];
   usort($photos,fn($a,$b)=>(int)($b['id']===$primary)<=>(int)($a['id']===$primary));
   // Upsert approved photographs; do not delete historical images or customer bookings.
   foreach($photos as $position=>$photo)$tour->images()->updateOrCreate(['path'=>$photo['path']],['alt'=>$photo['alt']['fr'],'credit'=>$photo['author'],'credit_url'=>$photo['source_page'],'position'=>$position]);
   // Keep the original JPEG as a fallback, ordered after the complete WebP gallery.
   $tour->images()->where('path','/assets/destinations/'.$slug.'.jpg')->update(['position'=>100]);
   Destination::where('slug',$slug)->update(['image'=>$cover['path']]);
  }
 });}
}
