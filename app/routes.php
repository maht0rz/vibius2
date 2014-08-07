<?php

/**********************************************************/

/*
	Require all dependencies
*/

use vibius\router as Route;
use vibius\View;

/**********************************************************/

/*
	Main routes
*/

Route::any('/',function(){
	View::load('welcome')->display();
});
