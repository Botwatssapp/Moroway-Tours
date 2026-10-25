<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TourImage extends Model { protected $guarded=['id']; protected function casts(): array {return [];} public function tour(){return $this->belongsTo(Tour::class);}public function toArray(){return [...parent::toArray(),...\App\Support\VerifiedMedia::metadata($this->path)];}}
