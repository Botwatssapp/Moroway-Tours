<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicDestinationSeeder extends Seeder {
 public function run(): void {
  $rows=json_decode(file_get_contents(database_path('data/public-destinations.json')),true,512,JSON_THROW_ON_ERROR);
  foreach($rows as $row){
   $existing=DB::table('destinations')->where('slug',$row['slug'])->first();
   if(!$existing){DB::table('destinations')->insert(['slug'=>$row['slug'],'name'=>$row['name'],'description'=>json_encode($row['description'],JSON_UNESCAPED_UNICODE),'image'=>$row['image'],'created_at'=>now(),'updated_at'=>now()]);continue;}
   // Keep destination IDs and genuine agency photos. Replace only the known generic seed photos.
   $updates=[];
   if(!$existing->image||in_array($existing->image,['/assets/merzouga-caravan.jpg','/assets/chefchaouen.jpg'],true))$updates['image']=$row['image'];
   $description=json_decode($existing->description??'null',true);
   if(!$description||!array_filter($description))$updates['description']=json_encode($row['description'],JSON_UNESCAPED_UNICODE);
   if($updates)DB::table('destinations')->where('id',$existing->id)->update([...$updates,'updated_at'=>now()]);
  }
 }
}
