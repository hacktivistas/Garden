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

