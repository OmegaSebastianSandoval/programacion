<?php 
/**
* clase que genera la insercion y edicion  de Tipos de boleta en la base de datos
*/
class Administracion_Model_DbTable_Boletatipo extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'boleta_tipo';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'boleta_tipo_id';

	/**
	 * insert recibe la informacion de un Boleta tipo y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$boleta_tipo_nombre = $data['boleta_tipo_nombre'];
		$query = "INSERT INTO boleta_tipo( boleta_tipo_nombre) VALUES ( '$boleta_tipo_nombre')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un Boleta tipo  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$boleta_tipo_nombre = $data['boleta_tipo_nombre'];
		$query = "UPDATE boleta_tipo SET  boleta_tipo_nombre = '$boleta_tipo_nombre' WHERE boleta_tipo_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}