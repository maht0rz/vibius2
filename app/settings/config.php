<?php

namespace vibius\app\settings;

class config{

    //Cache directory size limit in Kb
    public static $cacheFolderLimit = '0.20';

    //Cache file size limit in Kb
    public static $cacheFileLimit = '0.9';

    //Db connection details

    public static $type = "mysql";
    public static $host = "localhost";
    public static $dbname = "mydb";
    public static $username = "root";
    public static $password = "root";

}