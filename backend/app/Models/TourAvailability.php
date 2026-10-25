<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TourAvailability extends Model { protected $guarded=['id']; protected function casts(): array {return ['available'=>'boolean','date'=>'date:Y-m-d'];} public function tour(){return $this->belongsTo(Tour::class);}}
