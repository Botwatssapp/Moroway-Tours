<?php
use Illuminate\Database\Migrations\Migration;
return new class extends Migration {
 public function up(): void {(new \Database\Seeders\PublicDestinationSeeder)->run();}
 // Data-only migration: rollback deliberately preserves destination IDs and their relations.
 public function down(): void {}
};
