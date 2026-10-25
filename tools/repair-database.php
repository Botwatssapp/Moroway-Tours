<?php
// Run from the project root: php tools/repair-database.php --apply
// CLI only. Never invokes Artisan's migration runner or removes application records.
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if(PHP_SAPI!=='cli'||!in_array('--apply',$argv,true)){fwrite(STDERR,"Use the CLI with --apply.\n");exit(1);}
$c=config('database.connections.mysql');
if(config('database.default')!=='mysql'||!empty($c['url'])||!preg_match('/^[a-zA-Z0-9_]+$/',$c['database']))throw new RuntimeException('An explicit MySQL configuration and safe database name are required.');
try {
 $pdo=new PDO('mysql:host='.$c['host'].';port='.$c['port'].';charset=utf8mb4',$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_TIMEOUT=>5]);
 $pdo->exec('CREATE DATABASE IF NOT EXISTS `'.$c['database'].'` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
 echo "Database available: ".$c['database']." (server ".$c['host'].':'.$c['port'].").\n";
 DB::purge('mysql');
 $existing=Schema::getTableListing();$before=[];
 foreach($existing as $table)$before[$table]=DB::table($table)->count();
 $sql=file_get_contents(__DIR__.'/../backend/database/schema/mysql-bootstrap.sql');
 $sql=preg_replace('/^--.*$/m','',$sql);
 foreach(explode(';',$sql) as $statement){$statement=trim($statement);if($statement==='')continue;
  if(!preg_match('/^CREATE TABLE IF NOT EXISTS ([a-z_]+)\s*\(/',$statement,$match))throw new RuntimeException('Only additive table creation is allowed.');
  if(!Schema::hasTable($match[1])){DB::statement($statement);echo 'Created table: '.$match[1].PHP_EOL;}
 }
 if(!Schema::hasColumn('users','is_admin'))Schema::table('users',fn($table)=>$table->boolean('is_admin')->default(false));
 // Import only into an empty catalogue. Existing catalogues are left unchanged.
 if(DB::table('tours')->count()===0){DB::transaction(fn()=>(new Database\Seeders\DatabaseSeeder)->run());echo "City catalogue imported using the existing idempotent seeders.\n";}
 foreach($before as $table=>$count)if(DB::table($table)->count()<$count)throw new RuntimeException('Unexpected count decrease in '.$table);
 $summary=[];foreach(['users','destinations','tours','tour_images','tour_packages','bookings'] as $table)$summary[$table]=['exists'=>Schema::hasTable($table),'rows'=>DB::table($table)->count()];
 echo json_encode(['tables'=>$summary,'published_tours'=>DB::table('tours')->where('published',true)->count(),'existing_row_counts_preserved'=>true],JSON_PRETTY_PRINT).PHP_EOL;
}catch(Throwable $e){fwrite(STDERR,'Repair stopped (code '.$e->getCode().'). '.preg_replace('/(?:password|identified by)\s*[^\s,;]*/i','[redacted]',$e->getMessage()).PHP_EOL);exit(1);}
