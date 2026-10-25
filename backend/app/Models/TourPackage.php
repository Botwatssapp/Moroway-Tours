<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TourPackage extends Model { protected $guarded=['id']; protected function casts(): array {return [];} public function tour(){return $this->belongsTo(Tour::class);}}
