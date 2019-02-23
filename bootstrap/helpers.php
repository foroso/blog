<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}


function demo(){
    return 'this is demo function';
}