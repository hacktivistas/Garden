<?php 

// Evitamos que se ejecute fuera de la aplicación
if (!defined('APPLICATION')) exit();

// Información que aparece en el panel de gestión de plugins
$PluginInfo['Bankia'] = array(
	'Description' => 'Plugin para toqueabankia.net',
	'Version' => '0.1'
);

class BankiaPlugin extends Gdn_Plugin {

// Elimina tildes y sustituye espacios por guiones, para formar una url amistosa
// http://cubiq.org/the-perfect-php-clean-url-generator
private function urlAmistosa ($str) {
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_| -]+/", '-', $clean);
	return $clean;
}

// Devuelve un array bidimensional con el contenido del csv
// http://www.codedevelopr.com/articles/reading-csv-files-into-php-array/
private function leerCSV ($ruta_archivo, $delimitador){
	$archivo = fopen($ruta_archivo, 'r');
	while (!feof($archivo) ) {
		$lineas[] = fgetcsv($archivo, 1024, $delimitador);
	}
	fclose($archivo);
	return $lineas;
}

// Importante para recalcular la profundidad en el anidamiento de las categorías
// cuando editamos la tabla directamente
private function reconstruirArbolCategorias() {
	$CategoryModel = new CategoryModel();
	$CategoryModel->RebuildTree();
	unset($CategoryModel);
}

// inserta las sucursales y las provincias en la tabla Category
private function insertarSucursales() {
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
		$datoscategoria['ParentCategoryID'] = 1000;
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
				$SQL->Insert('Category', $datoscategoria);
				// registramos las provincias
				if (!in_array($csv[$i][3], $provincias)) {
					$provincias[] = $csv[$i][3];
				}
			}
		}
		// insertamos las provincias
		setlocale(LC_ALL, 'en_US.UTF8');
		unset($datoscategoria['CategoryID']);
		unset($datoscategoria['Description']);
		$datoscategoria['ParentCategoryID'] = 1001;
		foreach ($provincias as $provincia) {
			$datoscategoria['Name'] = $provincia;
			$datoscategoria['UrlCode'] = $this->urlAmistosa($provincia);
			$SQL->Insert('Category', $datoscategoria);
		}

		//reconstruimos el árbol
		$this->reconstruirArbolCategorias();
	} //if
}

// inserta un nuevo campo 'Sucursal' en la tabla User
private function insertarCampoSucursal() {
	$constructor = Gdn::Database()->Structure();
	$constructor->Table('User')->Column('Sucursal', 'int(11)')->Set();
}

// extrae el número de sucursal de una cadena con la forma oficina-num_sucursal
private function extraerOficina($url) {
	$resultado = 0;
	preg_match ('/oficina-([0-9]+)/', $url, $coincidencias);
	if (count($coincidencias)==2) $resultado = $coincidencias[1];
	return $resultado;
}

// Generamos los campos ocultos en los formularios de registro del Target
public function EntryController_Register_Handler($emisor) {
	$emisor->Form->AddHidden('Sucursal', $this->extraerOficina($_GET['Target']));
}

public function EntryController_ConnectData_Handler($emisor) {
        $emisor->Form->AddHidden('Sucursal', $this->extraerOficina($_GET['Target']));
}

// se ejecuta cada vez que se activa el plugin
public function Setup() {
	$this->insertarSucursales();
	$this->insertarCampoSucursal();
}

} //fin clase

