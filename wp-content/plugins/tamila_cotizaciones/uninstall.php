<?php
if(!defined('ABSPATH')) die();
//ésto para que no haga nada si se presionó la función por error
if(!defined('WP_UNINSTALL_PLUGIN')){
    die();
}
if(!function_exists('tamila_cotizaciones_eliminar')){
    function tamila_cotizaciones_eliminar(){
        global $wpdb;
       // $wpdb->query("drop table {$wpdb->prefix}tamila_cotizaciones_respuestas");
    }
}
tamila_cotizaciones_eliminar();