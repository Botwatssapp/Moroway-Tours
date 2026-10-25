<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('users',fn(Blueprint $t)=>$t->boolean('is_admin')->default(false));
  Schema::create('destinations',function(Blueprint $t){$t->id();$t->string('slug')->unique();$t->string('name');$t->json('description')->nullable();$t->string('image')->nullable();$t->timestamps();});
  Schema::create('tours',function(Blueprint $t){$t->id();$t->foreignId('destination_id')->nullable()->constrained()->nullOnDelete();$t->string('slug')->unique();$t->json('title');$t->json('description');$t->string('start_city')->index();$t->string('end_city');$t->unsignedSmallInteger('duration_days')->index();$t->string('kind')->index();$t->json('content')->nullable();$t->boolean('published')->default(true)->index();$t->timestamps();});
  Schema::create('tour_images',function(Blueprint $t){$t->id();$t->foreignId('tour_id')->constrained()->cascadeOnDelete();$t->string('path');$t->string('alt');$t->string('credit')->nullable();$t->string('credit_url')->nullable();$t->unsignedSmallInteger('position')->default(0);$t->timestamps();});
  Schema::create('tour_packages',function(Blueprint $t){$t->id();$t->foreignId('tour_id')->constrained()->cascadeOnDelete();$t->string('name');$t->text('description')->nullable();$t->timestamps();});
  Schema::create('tour_availabilities',function(Blueprint $t){$t->id();$t->foreignId('tour_id')->constrained()->cascadeOnDelete();$t->date('date');$t->unsignedSmallInteger('capacity');$t->boolean('available')->default(true);$t->unique(['tour_id','date']);$t->timestamps();});
  Schema::create('bookings',function(Blueprint $t){$t->id();$t->uuid('reference')->unique();$t->foreignId('tour_id')->nullable()->constrained()->restrictOnDelete();$t->foreignId('tour_package_id')->nullable()->constrained()->nullOnDelete();$t->string('name',100);$t->string('email');$t->string('whatsapp',30);$t->date('travel_date')->index();$t->unsignedSmallInteger('people');$t->string('pack',100);$t->string('request_type')->default('tour');$t->unsignedSmallInteger('duration')->nullable();$t->json('options')->nullable();$t->text('message')->nullable();$t->enum('status',['pending','confirmed','cancelled','completed'])->default('pending')->index();$t->decimal('final_quote',12,2)->nullable();$t->string('currency',3)->default('EUR');$t->timestamps();});
  Schema::create('contacts',function(Blueprint $t){$t->id();$t->string('name',100);$t->string('email');$t->text('message');$t->boolean('handled')->default(false);$t->timestamps();});
  Schema::create('reviews',function(Blueprint $t){$t->id();$t->foreignId('tour_id')->nullable()->constrained()->nullOnDelete();$t->string('name',100);$t->json('text');$t->unsignedTinyInteger('rating');$t->boolean('is_demo')->default(true);$t->boolean('published')->default(false);$t->timestamps();});
  Schema::create('site_settings',function(Blueprint $t){$t->id();$t->string('key')->unique();$t->text('value')->nullable();$t->timestamps();});
 }
 public function down(): void {foreach(['site_settings','reviews','contacts','bookings','tour_availabilities','tour_packages','tour_images','tours','destinations'] as $name)Schema::dropIfExists($name);Schema::table('users',fn(Blueprint $t)=>$t->dropColumn('is_admin'));}
};
