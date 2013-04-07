<?php
// Evitamos que se ejecute fuera de la aplicación
if (!defined('APPLICATION')) exit();

// http://robotgrrl.com/blog/2012/10/13/php-include-in-tpl-for-vanilla-theme-using-plugin/
//
// Creamos un nuevo tag para Smarty {misucursal}
function smarty_function_misucursal($params, $smarty) {
	echo Gdn::Session()->User->Sucursal;
}
