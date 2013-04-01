<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Bankia'] = array(
	'Description' => 'Plugin para toqueabankia.net',
	'Version' => '0.1'
);

class BankiaPlugin extends Gdn_Plugin {

//public $ModeloCategoria;

function leerCSV ($nombre_archivo, $delimitador){
//TODO mirar utf8  ( fopen_utf8() )
	setlocale(LC_ALL, 'es_ES.UTF-8');
        $archivo = fopen($nombre_archivo, 'r');
        while (!feof($archivo) ) {
                $linea[] = fgetcsv($archivo, 1024, $delimitador);
        }
        fclose($archivo);
        return $linea;
}

public function Setup() {

	$SQL = Gdn::Database()->SQL();

	// Si ya existe la categoria 100 no hacemos nada
	if ($SQL->GetWhere('Category', array('CategoryID' => 100))->NumRows() == 0) {

		// Creamos categorias padre
		// Sucursales ID=100
                $SQL->Insert('Category', array('CategoryID' => 100,
                	'Name' => 'Sucursales', 
                	'UrlCode' => 'sucursales',
                	'DateInserted' => Gdn_Format::ToDateTime(),
                	'DateUpdated' => Gdn_Format::ToDateTime(),
                        'ParentCategoryID' => -1
                ));

		// España ID=101
                $SQL->Insert('Category', array('CategoryID' => 101,
                        'Name' => 'España', 
                        'UrlCode' => 'espein',
                        'DateInserted' => Gdn_Format::ToDateTime(),
                        'DateUpdated' => Gdn_Format::ToDateTime(),
                        'ParentCategoryID' => -1 
                ));

		// Provincias (España) ID=102
                $SQL->Insert('Category', array('CategoryID' => 102,
                        'Name' => 'Provincias', 
                        'UrlCode' => 'provincias',
                        'DateInserted' => Gdn_Format::ToDateTime(),
                        'DateUpdated' => Gdn_Format::ToDateTime(),
			'ParentCategoryID' => 101
                ));

		// Internacional ID=103
                $SQL->Insert('Category', array('CategoryID' => 103,
                        'Name' => 'Internacional', 
                        'UrlCode' => 'internacional',
                        'DateInserted' => Gdn_Format::ToDateTime(),
                        'DateUpdated' => Gdn_Format::ToDateTime(),
                        'ParentCategoryID' => -1 
                ));


		/* GENERAMOS TODAS LAS SUCURSALES */
		//obtener csv a array
	        $csv = $this->leerCSV('/home/brankia/www/foros/plugins/Bankia/bankias.csv',';');
        	for ($i=1; $i<count($csv); $i++) {
                	$datoscategoria = array(
                        	'CategoryID' => 109+$i,
                        	'Name' => $csv[$i][2].'-'.$csv[$i][0],
                	        'UrlCode' => $csv[$i][7],
              	        	'Description' => $csv[$i][0].'<br>'.$csv[$i][1].'-'.$csv[$i][2].'<br>Tf: '.$csv[$i][4],
                        	'DateInserted' => Gdn_Format::ToDateTime(),
			        'DateUpdated' => Gdn_Format::ToDateTime(),
				'ParentCategoryID' => 100
			);

			$SQL->Insert('Category', $datoscategoria);
	        }

		//reconstruimos el árbol
		$CategoryModel = new CategoryModel();
		$CategoryModel->RebuildTree();
		unset($CategoryModel);

	} //if
/*
	//generamos la categoria sucursales y nos quedamos con su id
	$categoria = new CategoryModel();
	$id_padre = $categoria->Save(array('CategoryID' => -1, 'Name' => 'Sucursales', 'UrlCode' => 'sucursales'));

	//obtener csv a array
	$csv = $this->leerCSV('/home/brankia/www/foros/plugins/Bankia/bankias.csv',';');
	//$csv[1] = str_getcsv($csv[1], ';');

	//iteramos
	for ($i=1; $i<count($csv); $i++) {
		$categoria = new CategoryModel();
		$datoscategoria = array(
        		'CategoryID' => -1,
        		'Name' => $csv[$i][2].'-'.$csv[$i][0],
        		'UrlCode' => $csv[$i][7],
			'Description' => $csv[$i][0].'<br>'.$csv[$i][1].'-'.$csv[$i][2].'<br>Tf: '.$csv[$i][4],
			'ParentCategoryID' => $id_padre
        	);
        	$categoria->Save($datoscategoria);
	}

*/

	/* $categoria = new CategoryModel();
	$datoscategoria = array(
	'CategoryID' => -1,
	'Name' => 'Provincias',
	'UrlCode' => 'provincias'
	);
	$categoria->Save($datoscategoria);*/
	SaveToConfig('Plugin.Bankia.Prueba', 'tikitiki'); 
}

public function OnDisable() {
	/*$categoria = new CategoryModel();
	$borrar = $categoria->GetByCode('provincias');
	$categoria->Delete($borrar);*/
	// TODO: buscar un método para borrar todo el arbol de golpe, que me suena haberlo visto
	RemoveFromconfig('Plugin.Bankia.Prueba');
}

}
