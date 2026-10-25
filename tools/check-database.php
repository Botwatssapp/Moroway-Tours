<?php
// CLI diagnostics: credentials are read from Laravel and never printed.
require __DIR__.'/../backend/vendor/autoload.php';
$app=require __DIR__.'/../backend/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$c=config('database.connections.mysql');
echo json_encode(['driver'=>config('database.default'),'host'=>$c['host'],'port'=>$c['port'],'database'=>$c['database'],'configuration_cached'=>$app->configurationIsCached()],JSON_PRETTY_PRINT).PHP_EOL;
try {
 $pdo=new PDO('mysql:host='.$c['host'].';port='.$c['port'].';charset=utf8mb4',$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_TIMEOUT=>5]);
 $query=$pdo->prepare('SELECT SCHEMA_NAME,DEFAULT_CHARACTER_SET_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME=?');$query->execute([$c['database']]);
 echo json_encode(['server_connection'=>'ok','schema'=>$query->fetch(PDO::FETCH_ASSOC)?:null],JSON_PRETTY_PRINT).PHP_EOL;
 $q=$pdo->prepare('SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA=? ORDER BY TABLE_NAME');$q->execute([$c['database']]);echo json_encode(['tables'=>$q->fetchAll(PDO::FETCH_COLUMN)],JSON_PRETTY_PRINT).PHP_EOL;
}catch(Throwable $e){echo 'Connection failed (code '.$e->getCode().'). No credentials displayed.'.PHP_EOL;exit(1);}
