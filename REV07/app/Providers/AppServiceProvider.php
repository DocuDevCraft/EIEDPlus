<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		$host = Request::getHttpHost();
		$port = Request::getPort();
		
		if($host === 'plusadmin.eied.com'){
			if($port == 8080){
				URL::forceScheme('http');
			}else {
				URL::forceScheme('https');
			}
		}
		
        //if($this->app->environment('production')){
		//	URL::forceScheme('https');
		//}
    }
}
