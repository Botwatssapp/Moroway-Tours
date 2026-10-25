<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Booking extends Model { protected $guarded=['id']; protected function casts(): array {return ['options'=>'array','travel_date'=>'date:Y-m-d','final_quote'=>'decimal:2'];} public function tour(){return $this->belongsTo(Tour::class);} public function package(){return $this->belongsTo(TourPackage::class,"tour_package_id");}}
