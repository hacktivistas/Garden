<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Bankia'] = array(
	'Description' => 'Plugin para toqueabankia.net',
	'Version' => '0.1'
);

class BankiaPlugin extends Gdn_Plugin {

/* devuelve un array bidimensional con el contenido del csv */
private function leerCSV ($nombre_archivo, $delimitador){
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

	// Si ya existe la categoria con ID 1000 no hacemos nada
	if ($SQL->GetWhere('Category', array('CategoryID' => 1000))->NumRows() == 0) {

		// Creamos categorias padre: Sucursales y Provincias
		$datoscategoria = array(
			'CategoryID' => 1000,
			'DateInserted' => Gdn_Format::ToDateTime(),
			'DateUpdated' => Gdn_Format::ToDateTime(),
			'ParentCategoryID' => -1
		);
		// Sucursales ID=1000
		$datoscategoria['Name']='Sucursales';
		$datoscategoria['UrlCode']='sucursales';
		$SQL->Insert('Category', $datoscategoria);
		// Provincias ID=1001
		$datoscategoria['CategoryID']=1001;
		$datoscategoria['Name']='Provincias';
		$datoscategoria['UrlCode']='provincias';
		$SQL->Insert('Category', $datoscategoria);

		// GENERAMOS TODAS LAS SUCURSALES Y PROVINCIAS A PARTIR DEL CSV
		$provincias = array();
		// convertimos el CSV a array bidimensional
		$csv = $this->leerCSV(PATH_PLUGINS.'/Bankia/bankias.csv',';');
		$num_lineas = count($csv);
		// iteramos descartando la primera línea e insertamos sucursales
		for ($i=1; $i<$num_lineas; $i++) {
			if ($csv[$i][7]) {
				$datoscategoria = array(
				'CategoryID' => 1009+$i,
				'Name' => 'Oficina '.$csv[$i][7],
				'UrlCode' => 'oficina-'.$csv[$i][7],
				'Description' => $csv[$i][0].'<br>'.$csv[$i][1].'-'.$csv[$i][2].'<br>Tf: '.$csv[$i][4],
				'ParentCategoryID' => 1000
				);
				$SQL->Insert('Category', $datoscategoria);
				// registramos las provincias
				if (!in_array($csv[$i][3], $provincias)) {
					$provincias[] = $csv[$i][3];
				}
			}
		}
		// insertamos las provincias
		foreach ($provincias as $provincia) {
			$datoscategoria = array(
			'CategoryID' => NULL,
			'Name' => $provincia,
			'UrlCode' => strtolower($provincia),
			'Description' => NULL,
			'ParentCategoryID' => 1001
			);
			$SQL->Insert('Category', $datoscategoria);
		}

		//reconstruimos el árbol
		$CategoryModel = new CategoryModel();
		$CategoryModel->RebuildTree();
		unset($CategoryModel);

} //fin Setup
} //fin clase

/*


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



		//obtener csv a array
	        $csv = $this->leerCSV(PATH_PLUGINS.'/Bankia/bankias.csv',';');
        	for ($i=1; $i<count($csv); $i++) {
			if ($csv[$i][7]) {
                	$datoscategoria = array(
                        	'CategoryID' => 109+$i,
				'Name' => 'Oficina '.$csv[$i][7],
                	        'UrlCode' => 'oficina-'.$csv[$i][7],
              	        	'Description' => $csv[$i][0].'<br>'.$csv[$i][1].'-'.$csv[$i][2].'<br>Tf: '.$csv[$i][4],
                        	'DateInserted' => Gdn_Format::ToDateTime(),
			        'DateUpdated' => Gdn_Format::ToDateTime(),
				'ParentCategoryID' => 100
			);


			$SQL->Insert('Category', $datoscategoria);
			}
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
	// TODO: buscar un método para borrar todo el arbol de golpe, que me suena haberlo visto
	RemoveFromconfig('Plugin.Bankia.Prueba');
}

} */
