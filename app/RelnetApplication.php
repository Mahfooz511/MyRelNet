<?php namespace App;

use Illuminate\Foundation\Application;

class RelnetApplication extends Application  
{
	public function publicPath()  
	{
	    return $this->basePath . ‘/public_html’;
	}
}

