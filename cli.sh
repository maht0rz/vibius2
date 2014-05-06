#!/bin/bash
echo "Vibius CLI"
create (){
    cp -r . $1
    echo "New Vibius app has been created."
}

install (){
    composer install
    echo "Vibius has been created."
}