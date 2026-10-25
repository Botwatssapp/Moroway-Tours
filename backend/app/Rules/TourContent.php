<?php
namespace App\Rules;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
class TourContent implements ValidationRule {
 public function validate(string $attribute,mixed $value,Closure $fail): void {
  if($value===null)return;
  if(!is_array($value)){$fail('Le contenu doit être un objet.');return;}
  $rules=['route'=>'nullable|array','presentation'=>'nullable|array:fr,en','presentation.*'=>'array:overview,highlights,days,includes,map','presentation.*.overview'=>'nullable|string|max:10000','presentation.*.highlights'=>'nullable|array|max:30','presentation.*.highlights.*'=>'array:name,activity','presentation.*.highlights.*.name'=>'required|string|max:255','presentation.*.highlights.*.activity'=>'required|string|max:2000','presentation.*.days'=>'nullable|array|max:60','presentation.*.days.*'=>'array:title,intro,segments,night,closing,places,photo','presentation.*.days.*.title'=>'required|string|max:500','presentation.*.days.*.intro'=>'nullable|string|max:10000','presentation.*.days.*.night'=>'nullable|string|max:1000','presentation.*.days.*.closing'=>'nullable|string|max:10000','presentation.*.days.*.places'=>'nullable|array|max:100','presentation.*.days.*.places.*'=>'string|max:255','presentation.*.days.*.photo'=>'nullable|string|max:100','presentation.*.days.*.segments'=>'nullable|array|max:30','presentation.*.days.*.segments.*'=>'array:title,text','presentation.*.days.*.segments.*.title'=>'required|string|max:255','presentation.*.days.*.segments.*.text'=>'required|string|max:10000','presentation.*.includes'=>'nullable|array|max:100','presentation.*.includes.*'=>'string|max:2000','presentation.*.map'=>['nullable','url:https','regex:~^https://www\.google\.com/maps/~']];
  $v=Validator::make($value,$rules);if($v->fails())$fail('Programme invalide : '.$v->errors()->first());
 }
}
