<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Composers\NavigationComposer;



class ViewComposerServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->ComposeNavigation() ;
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	private function ComposeNavigation(){

		//view()->composer(	'*','App\Http\Composers\NavigationComposer');  // will call 'compose'
		view()->composer('*','App\Http\Composers\NavigationComposer@compose');  // will call 'compose'
		view()->composer(['familyhome','addmember','deletemember','share','editform',
			              'map','accesschange','setroot','list','share','addrelation', 'deleterelation'],'App\Http\Composers\NavigationComposer@faminfocompose');

	}

}
