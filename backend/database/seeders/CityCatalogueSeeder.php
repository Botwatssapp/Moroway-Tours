<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{Tour,Destination};
use App\Support\PublicCatalogue;

class CityCatalogueSeeder extends Seeder {
 public function run(): void {
  DB::transaction(function(){
   $this->call(PublicDestinationSeeder::class);
   // Archive out-of-scope offers. Their IDs, media, packs and booking relations remain intact.
   Tour::where('published',true)->get()->each(function($tour){if(!PublicCatalogue::allows($tour))$tour->update(['published'=>false]);});
   $cities=json_decode(file_get_contents(database_path('data/public-destinations.json')),true,512,JSON_THROW_ON_ERROR);
   foreach($cities as $city){
    $name=$city['name'];$slug=$city['slug'].'-decouverte';
    $presentation=[];
    foreach(['fr','en'] as $language){$fr=$language==='fr';
     $presentation[$language]=[
      'overview'=>$fr?"Une proposition de découverte de $name, à pied et à votre rythme. Le parcours reste exclusivement dans la ville et sera défini avec l’agence.":"A proposed discovery of $name, on foot and at your own pace. The route stays within the city and will be agreed with the agency.",
      'highlights'=>[['name'=>$name,'activity'=>$fr?'Patrimoine urbain, promenades et pauses photo selon vos envies.':'Urban heritage, walks and photo stops around your interests.']],
      'days'=>[['title'=>$fr?"Découverte de $name":"Discover $name",'intro'=>$fr?'Retrouvez votre accompagnateur au point de rendez-vous convenu dans la ville.':'Meet your host at the agreed meeting point in the city.','segments'=>[['title'=>$name,'text'=>$fr?'Le programme peut associer promenade, découverte du patrimoine et temps libre. Les lieux visités, entrées et horaires sont à confirmer dans votre devis.':'The programme may combine walking, heritage discovery and free time. Places visited, admission and times will be confirmed in your quote.']],'night'=>$fr?'Aucune nuitée prévue.':'No overnight stay planned.','closing'=>$fr?"Fin de la visite à $name, au point convenu.":"The visit ends in $name at the agreed point.",'places'=>[$name],'photo'=>$city['slug']]],
      'includes'=>[$fr?'Parcours urbain et accompagnement selon les prestations confirmées au devis.':'City route and guidance according to the services confirmed in your quote.'],
      'map'=>'https://www.google.com/maps/search/?api=1&query='.rawurlencode($name.', Morocco')
     ];
    }
    $tour=Tour::firstOrCreate(['slug'=>$slug],[
     'destination_id'=>Destination::where('slug',$city['slug'])->value('id'),
     'title'=>['fr'=>"$name, à votre rythme",'en'=>"$name, at your own pace"],
     'description'=>['fr'=>"Une journée de découverte à $name : patrimoine, promenades et rencontres. Programme proposé, à confirmer avec l’agence.",'en'=>"A day discovering $name: heritage, walks and encounters. Proposed programme, to be confirmed with the agency."],
     'start_city'=>$name,'end_city'=>$name,'duration_days'=>1,'kind'=>'culture','published'=>true,
     'content'=>['region_scope'=>PublicCatalogue::MARKER,'route'=>['plans'=>["Découverte de $name"],'start'=>$name,'end'=>$name,'n'=>1,'places'=>[$name],'visitPlaces'=>[$name],'photoKeys'=>[$city['slug']],'kind'=>'culture'],'presentation'=>$presentation]
    ]);
    if($tour->wasRecentlyCreated){$tour->images()->create(['path'=>$city['image'],'alt'=>$name,'credit'=>$city['image_credit'],'credit_url'=>explode(' — ',$city['image_credit'])[2],'position'=>0]);$tour->packages()->create(['name'=>'Découverte urbaine','description'=>'Programme, accompagnement, options et prix à confirmer par devis.']);}
   }
  });
 }
}
