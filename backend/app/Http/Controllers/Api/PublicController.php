<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\{BookingRequest,ContactRequest};
use App\Models\{Tour,Destination,Booking,Contact,Review,SiteSetting};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class PublicController extends Controller {
 public function tours(Request $r){
  $v=$r->validate(['q'=>'nullable|string|max:100','kind'=>'nullable|in:private,shared,itineraries,daytrips,culture','city'=>'nullable|string|max:100','destination'=>'nullable|string|max:100','days'=>'nullable|integer|min:1|max:60']);
  $q=Tour::with(['images','packages','destination'])->publicCatalogue();
  foreach(['city','destination'] as $key)if($r->filled($key)&&!in_array($v[$key],\App\Support\PublicCatalogue::CITIES,true))return response()->json([]);
  if($r->filled('kind'))$q->where('kind',$v['kind']);
  if($r->filled('city'))$q->where('start_city',$v['city']);
  if($r->filled('days'))$q->where('duration_days','<=',$v['days']);
  if($r->filled('destination'))$q->whereHas('destination',fn($d)=>$d->where('name',$v['destination']));
  if($r->filled('q')){$term='%'.$v['q'].'%';$q->where(fn($q)=>$q->where('title->fr','like',$term)->orWhere('title->en','like',$term));}
  return $q->orderBy('id')->get()->filter(fn($tour)=>\App\Support\PublicCatalogue::allows($tour))->map(fn($tour)=>\App\Support\VerifiedMedia::decorateTour($tour))->values();
 }
 public function tour(string $slug){
  $tour=Tour::with(['images','packages','destination','availabilities'=>fn($q)=>$q->where('date','>=',today())])->publicCatalogue()->where('slug',$slug)->firstOrFail();
  abort_unless(\App\Support\PublicCatalogue::allows($tour),404);return \App\Support\VerifiedMedia::decorateTour($tour);
 }
 public function destinations(){
  $credits=collect(json_decode(file_get_contents(database_path('data/public-destinations.json')),true))->keyBy('slug');
  return Destination::whereIn('slug',Destination::PUBLIC_SLUGS)->get()->sortBy(fn($d)=>array_search($d->slug,Destination::PUBLIC_SLUGS,true))->values()->map(function($d)use($credits){
   $data=$d->toArray();$canonical=$credits[$d->slug];$data['name']=$canonical['name'];if(\App\Support\PublicCatalogue::hasExcludedContent($data['description']))$data['description']=$canonical['description'];$photo=collect(\App\Support\VerifiedMedia::all())->first(fn($photo)=>$photo['city']===$canonical['name']);$data['image']=$photo['path']??$canonical['image'];$data['image_credit']=$photo['credit']??$canonical['image_credit'];$data['image_metadata']=\App\Support\VerifiedMedia::metadata($data['image']);return $data;
  });
 }
 public function reviews(){return Review::where('published',true)->get()->filter(function($review){if(\App\Support\PublicCatalogue::hasExcludedContent([$review->name,$review->text]))return false;if(!$review->tour_id)return true;$tour=Tour::publicCatalogue()->find($review->tour_id);return $tour&&\App\Support\PublicCatalogue::allows($tour);})->values();}
 public function settings(){return SiteSetting::whereIn('key',['whatsapp','email','instagram','tiktok','facebook','home_tagline_fr','home_tagline_en','contact_address'])->pluck('value','key')->filter(fn($value)=>!\App\Support\PublicCatalogue::hasExcludedContent($value??''));}
 public function contact(ContactRequest $r){$data=$r->safe()->only(['name','email','message']);Contact::create($data);return response()->json(['message'=>'Message enregistré.'],201);}
 public function booking(BookingRequest $r){$data=$r->safe()->except(['consent','website','departure_city']);if($r->filled('departure_city'))$data['options']=[...($data['options']??[]),$r->input('departure_city')];$booking=DB::transaction(function()use($data){
  if(!empty($data['tour_id'])){
   $tour=Tour::publicCatalogue()->lockForUpdate()->findOrFail($data['tour_id']);if(!\App\Support\PublicCatalogue::allows($tour))throw ValidationException::withMessages(['tour_id'=>'Cette excursion est indisponible.']);
   if(!empty($data['tour_package_id'])&&!$tour->packages()->whereKey($data['tour_package_id'])->exists())throw ValidationException::withMessages(['tour_package_id'=>'Ce pack ne correspond pas au circuit.']);
   $availability=$tour->availabilities()->whereDate('date',$data['travel_date'])->first();
   if($availability){$reserved=Booking::where('tour_id',$tour->id)->whereDate('travel_date',$data['travel_date'])->whereIn('status',['pending','confirmed','completed'])->sum('people');if(!$availability->available||$reserved+$data['people']>$availability->capacity)throw ValidationException::withMessages(['travel_date'=>'Places indisponibles pour cette date.']);}
  }elseif(!empty($data['tour_package_id']))throw ValidationException::withMessages(['tour_package_id'=>'Un circuit est requis pour ce pack.']);
  return Booking::create([...$data,'reference'=>(string)Str::uuid(),'status'=>'pending']);
 });return response()->json(['reference'=>$booking->reference,'status'=>$booking->status],201);}
}
