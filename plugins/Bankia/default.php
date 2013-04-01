<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Bankia'] = array(
	'Description' => 'Plugin para toqueabankia.net',
	'Version' => '0.1'
);

class BankiaPlugin extends Gdn_Plugin {

//public $ModeloCategoria;

public function Setup() {
	$categoria = new CategoryModel();
	$datoscategoria = array(
	'CategoryID' => -1,
	'Name' => 'Provincias',
	'UrlCode' => 'provincias'
	);
	$categoria->Save($datoscategoria);
	SaveToConfig('Plugin.Bankia.Prueba', 'tikitiki');
}

public function OnDisable() {
	$categoria = new CategoryModel();
	$borrar = $categoria->GetByCode('provincias');
	$categoria->Delete($borrar);
	RemoveFromconfig('Plugin.Bankia.Prueba');
}

}
