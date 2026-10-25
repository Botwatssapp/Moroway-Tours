<?php
return ['paths'=>['api/*','sanctum/csrf-cookie','login','logout'],'allowed_methods'=>['GET','POST','PUT','PATCH','DELETE','OPTIONS'],'allowed_origins'=>[env('FRONTEND_URL','http://127.0.0.1:5173')],'allowed_origins_patterns'=>[],'allowed_headers'=>['Content-Type','X-XSRF-TOKEN','X-Requested-With','Accept','Origin'],'exposed_headers'=>[],'max_age'=>0,'supports_credentials'=>true];
