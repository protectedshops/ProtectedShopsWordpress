<?php
/*
Plugin Name: ProtectedShops
*/

register_activation_hook( __FILE__, 'protectedshops_activate' );

function activate()
{
    echo "I am active"; exit;
}