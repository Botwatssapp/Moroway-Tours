<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Tour extends Model {
 protected $guarded=['id'];
 protected function casts(): array {return ['title'=>'array','description'=>'array','content'=>'array','published'=>'boolean'];}
 public function scopePublicCatalogue($query){return $query->where('published',true)->whereIn('start_city',\App\Support\PublicCatalogue::CITIES)->whereIn('end_city',\App\Support\PublicCatalogue::CITIES)->where('content->region_scope',\App\Support\PublicCatalogue::MARKER)->whereHas('destination',fn($d)=>$d->whereIn('slug',Destination::PUBLIC_SLUGS));}
 public function destination(){return $this->belongsTo(Destination::class);}
 public function images(){return $this->hasMany(TourImage::class)->orderBy('position');}
 public function packages(){return $this->hasMany(TourPackage::class);}
 public function availabilities(){return $this->hasMany(TourAvailability::class);}
 public function bookings(){return $this->hasMany(Booking::class);}
}
