<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class BookingRequest extends FormRequest {
 public function authorize(): bool{return true;}
 public function rules(): array{return [
 'name'=>'required|string|max:100','email'=>'required|email:rfc|max:254','whatsapp'=>['required','string','regex:/^\+?[0-9\s()\-]{8,25}$/'],
 'tour_id'=>['nullable','required_if:request_type,tour',Rule::exists('tours','id')->where(fn($q)=>$q->where('published',true)->whereIn('start_city',\App\Support\PublicCatalogue::CITIES)->whereIn('end_city',\App\Support\PublicCatalogue::CITIES)->where('content->region_scope',\App\Support\PublicCatalogue::MARKER))],
 'tour_package_id'=>'nullable|integer|exists:tour_packages,id','travel_date'=>'required|date_format:Y-m-d|after_or_equal:today','people'=>'required|integer|min:1|max:200','pack'=>'required|string|max:100',
 'message'=>'nullable|string|max:3000','request_type'=>'required|in:tour,custom,students','duration'=>'nullable|integer|min:1|max:60',
 'options'=>'nullable|array|max:10','options.*'=>['string','max:200',function($attribute,$value,$fail){if(\App\Support\PublicCatalogue::hasExcludedContent($value))$fail('Choisissez une prestation à Fès, Tanger ou Casablanca.');}],'departure_city'=>['nullable',Rule::in(\App\Support\PublicCatalogue::CITIES)],'consent'=>'accepted','website'=>'prohibited'];}
}
