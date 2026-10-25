<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Review extends Model { protected $guarded=['id']; protected function casts(): array {return ['text'=>'array','published'=>'boolean','is_demo'=>'boolean'];} public function tour(){return $this->belongsTo(Tour::class);}}
