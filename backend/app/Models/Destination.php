<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Destination extends Model {
 public const PUBLIC_SLUGS=['fes','tanger','casablanca'];
 protected $guarded=['id'];
 protected function casts(): array {return ['description'=>'array'];}
 public function tours(){return $this->hasMany(Tour::class);}
}
