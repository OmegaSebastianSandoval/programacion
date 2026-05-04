<?php 
/**
* clase que genera la insercion y edicion  de testts en la base de datos
*/
class Administracion_Model_DbTable_Testts extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'test_table';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'id';

	/**
	 * insert recibe la informacion de un testt y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$texto = $data['texto'];
		$numero = $data['numero'];
		$correo = $data['correo'];
		$fecha = $data['fecha'];
		$contrasenia = $data['contrasenia'];
		$tiny = $data['tiny'];
		$text_area = $data['text_area'];
		$select = $data['select'];
		$checkbox = $data['checkbox'];
		$image = $data['image'];
		$file = $data['file'];
		$query = "INSERT INTO test_table( texto, numero, correo, fecha, contrasenia, tiny, text_area, `select`, checkbox, `image`, `file`) VALUES ( '$texto', '$numero', '$correo', '$fecha', '$contrasenia', '$tiny', '$text_area', '$select', '$checkbox', '$image', '$file')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un testt  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$texto = $data['texto'];
		$numero = $data['numero'];
		$correo = $data['correo'];
		$fecha = $data['fecha'];
		$contrasenia = $data['contrasenia'];
		$tiny = $data['tiny'];
		$text_area = $data['text_area'];
		$select = $data['select'];
		$checkbox = $data['checkbox'];
		$image = $data['image'];
		$file = $data['file'];
		$query = "UPDATE test_table SET  texto = '$texto', numero = '$numero', correo = '$correo', fecha = '$fecha', contrasenia = '$contrasenia', tiny = '$tiny', text_area = '$text_area', `select` = '$select', checkbox = '$checkbox', `image` = '$image', `file` = '$file' WHERE id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}