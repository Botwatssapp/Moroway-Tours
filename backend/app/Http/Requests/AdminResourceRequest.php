<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class AdminResourceRequest extends FormRequest {
 public function authorize(): bool{return $this->user()?->can('manage-site')??false;}
 public function withValidator($validator): void {$validator->after(function($validator){if($this->route('resource')==='tours'&&$this->has('content'))(new \App\Rules\TourContent)->validate('content',$this->input('content'),fn($message)=>$validator->errors()->add('content',$message));});}
 public function rules(): array {
  $id=$this->route('id');$sometimes=$this->isMethod('post')?'required':'sometimes';
  $bilingual=fn($field)=>[$field=>"$sometimes|array:fr,en","$field.fr"=>"required_with:$field|string|max:10000","$field.en"=>"required_with:$field|string|max:10000"];
  return match($this->route('resource')){
   'tours'=>array_merge($bilingual('title'),$bilingual('description'),['slug'=>[$sometimes,'alpha_dash','max:150',Rule::unique('tours')->ignore($id)],'destination_id'=>'nullable|exists:destinations,id','start_city'=>"$sometimes|string|max:100",'end_city'=>"$sometimes|string|max:100",'duration_days'=>"$sometimes|integer|min:1|max:60",'kind'=>"$sometimes|in:private,shared,itineraries,daytrips,culture",'published'=>'sometimes|boolean','content'=>'nullable|array','content.route'=>'nullable|array','content.presentation'=>'nullable|array']),
   'destinations'=>array_merge($bilingual('description'),['name'=>"$sometimes|string|max:100",'slug'=>[$sometimes,'alpha_dash','max:150',Rule::unique('destinations')->ignore($id)],'image'=>['nullable','string','max:500','regex:~^(?:/assets/|/storage/|https://)~']]),
   'packages'=>['tour_id'=>"$sometimes|exists:tours,id",'name'=>"$sometimes|string|max:100",'description'=>'nullable|string|max:3000'],
   'availabilities'=>['tour_id'=>"$sometimes|exists:tours,id",'date'=>[$sometimes,'date_format:Y-m-d','after_or_equal:today',Rule::unique('tour_availabilities')->where('tour_id',$this->input('tour_id'))->ignore($id)],'capacity'=>"$sometimes|integer|min:0|max:200",'available'=>'sometimes|boolean'],
   'bookings'=>['status'=>'sometimes|in:pending,confirmed,cancelled,completed','final_quote'=>'nullable|numeric|min:0|max:99999999.99','currency'=>'sometimes|in:EUR,MAD,USD,GBP'],
   'contacts'=>['handled'=>'required|boolean'],
   'reviews'=>array_merge($bilingual('text'),['tour_id'=>'nullable|exists:tours,id','name'=>"$sometimes|string|max:100",'rating'=>"$sometimes|integer|min:1|max:5",'published'=>'sometimes|boolean','is_demo'=>'sometimes|boolean']),
   default=>[]
  };
 }
}
