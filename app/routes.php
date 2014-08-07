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
	echo "vibius framework 2.1 dev branch";
});

