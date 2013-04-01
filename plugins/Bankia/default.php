<?php if (!defined('APPLICATION')) exit();

$PluginInfo['Bankia'] = array(
	'Description' => 'Plugin para toqueabankia.net',
	'Version' => '0.1'
);

class BankiaPlugin extends Gdn_Plugin {

private function urlAmistosa ($str) {
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_| -]+/", '-', $clean);
	return $clean;
}

/* devuelve un array bidimensional con el contenido del csv */
private function leerCSV ($nombre_archivo, $delimitador){
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
		setlocale(LC_ALL, 'es_ES.UTF-8');
		// convertimos el CSV a array bidimensional
		$csv = $this->leerCSV(PATH_PLUGINS.'/Bankia/bankias.csv',';');
		$num_lineas = count($csv);
		// iteramos descartando la primera línea e insertamos sucursales
		for ($i=1; $i<$num_lineas; $i++) {
			if ($csv[$i][7]) {
				$datoscategoria['CategoryID'] = 1009+$i;
				$datoscategoria['Name'] = 'Oficina '.$csv[$i][7];
				$datoscategoria['UrlCode'] = 'oficina-'.$csv[$i][7];
				$datoscategoria['Description'] = $csv[$i][0].'<br>'.$csv[$i][1].'-'.$csv[$i][2].'<br>Tf: '.$csv[$i][4];
				$datoscategoria['ParentCategoryID'] = 1000;
				$SQL->Insert('Category', $datoscategoria);
				// registramos las provincias
				if (!in_array($csv[$i][3], $provincias)) {
					$provincias[] = $csv[$i][3];
				}
			}
		}		
		setlocale(LC_ALL, 'en_US.UTF8');
		// insertamos las provincias
		foreach ($provincias as $provincia) {
			unset($datoscategoria['CategoryID']);
			$datoscategoria['Name'] = $provincia;
			$datoscategoria['UrlCode'] = $this->urlAmistosa($provincia);
			unset($datoscategoria['Description']);
			$datoscategoria['ParentCategoryID'] = 1001;
			$SQL->Insert('Category', $datoscategoria);
		}

		//reconstruimos el árbol
		$CategoryModel = new CategoryModel();
		$CategoryModel->RebuildTree();
		unset($CategoryModel);
	} //if
} //fin Setup
} //fin clase

