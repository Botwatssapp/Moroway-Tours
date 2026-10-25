<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminResourceRequest;
use App\Models\{Tour,Destination,TourPackage,TourAvailability,Booking,Contact,Review,SiteSetting,TourImage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
class AdminController extends Controller {
 private function model(Request $r): string{return match($r->route('resource')){'tours'=>Tour::class,'destinations'=>Destination::class,'packages'=>TourPackage::class,'availabilities'=>TourAvailability::class,'bookings'=>Booking::class,'contacts'=>Contact::class,'reviews'=>Review::class,default=>abort(404)};}
 public function stats(){return ['bookings'=>Booking::count(),'pending'=>Booking::where('status','pending')->count(),'confirmed'=>Booking::where('status','confirmed')->count(),'contacts'=>Contact::where('handled',false)->count(),'tours'=>Tour::count()];}
 public function index(Request $r){$m=$this->model($r);$q=$m::query()->orderByDesc('id');if($m===Booking::class)$q->with('tour');if($m===Tour::class)$q->with(['images','packages','availabilities']);return $q->paginate(100);}
 public function store(AdminResourceRequest $r){$m=$this->model($r);return response()->json($m::create($r->validated()),201);}
 public function update(AdminResourceRequest $r){$m=$this->model($r);$row=$m::findOrFail($r->route('id'));$row->update($r->validated());return $row->fresh();}
 public function destroy(Request $r){$m=$this->model($r);$row=$m::findOrFail($r->route('id'));if($row instanceof Tour&&$row->bookings()->exists())return response()->json(['message'=>'Ce circuit possède des réservations. Dépubliez-le pour conserver leur historique.'],409);if($row instanceof Tour){foreach($row->images as $image)$this->removeFile($image);}$row->delete();return response()->noContent();}
 public function settings(Request $r){$rules=['whatsapp'=>['nullable','regex:/^\+?[0-9\s()\-]{8,25}$/'],'email'=>'nullable|email|max:254','contact_address'=>'nullable|string|max:1000','home_tagline_fr'=>'nullable|string|max:500','home_tagline_en'=>'nullable|string|max:500'];foreach(['instagram','facebook','tiktok'] as $network)$rules[$network]=['nullable','url:https','max:500',function($a,$v,$fail)use($network){if(!in_array(parse_url($v,PHP_URL_HOST),[$network.'.com','www.'.$network.'.com',...($network==='facebook'?['m.facebook.com']:[])]))$fail('Domaine du réseau invalide.');}];$data=$r->validate($rules);foreach($data as $key=>$value)SiteSetting::updateOrCreate(['key'=>$key],['value'=>$value]);return SiteSetting::pluck('value','key');}
 public function upload(Request $r,Tour $tour){$r->validate(['image'=>'required|file|image|mimes:jpeg,png,webp,avif|max:5120|dimensions:max_width=8000,max_height=8000','alt'=>'required|string|max:255','credit'=>'nullable|string|max:255','credit_url'=>'nullable|url:https|max:255']);$path=$r->file('image')->store('tours','public');$image=$tour->images()->create(['path'=>'/storage/'.$path,'alt'=>$r->input('alt'),'credit'=>$r->input('credit'),'credit_url'=>$r->input('credit_url'),'position'=>$tour->images()->max('position')+1]);return response()->json($image,201);}
 private function removeFile(TourImage $image): void{if(str_starts_with($image->path,'/storage/tours/'))Storage::disk('public')->delete(substr($image->path,9));}
 public function deleteImage(TourImage $image){$this->removeFile($image);$image->delete();return response()->noContent();}
}
