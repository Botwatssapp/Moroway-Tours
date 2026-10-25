<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('moroway:create-admin {email}',function(){
 $email=$this->argument('email');if(!filter_var($email,FILTER_VALIDATE_EMAIL)){ $this->error('Adresse e-mail invalide.');return 1;}
 if(\App\Models\User::where('email',$email)->exists()){$this->error('Cette adresse existe déjà.');return 1;}
 $name=$this->ask('Nom');$password=$this->secret('Mot de passe (12 caractères minimum)');
 if(mb_strlen($password??'')<12){$this->error('Mot de passe trop court.');return 1;}
 $user=new \App\Models\User(['name'=>$name?:'Administrateur','email'=>$email,'password'=>$password]);$user->is_admin=true;$user->save();$this->info('Administrateur créé.');
})->purpose('Créer un administrateur sans identifiant prédéfini');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
