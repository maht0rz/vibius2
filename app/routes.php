<?php

/**********************************************************/

/*
	Require all dependencies
*/

use vibius\router as Route;

/**********************************************************/

/*
	Main routes
*/

Route::any('/',function(){
	echo "welcome to vibius2.1";
});
