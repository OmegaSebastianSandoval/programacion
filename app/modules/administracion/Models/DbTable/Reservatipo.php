<?php 
/**
* clase que genera la insercion y edicion  de Tipos reservas en la base de datos
*/
class Administracion_Model_DbTable_Reservatipo extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'reserva_tipo';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'reserva_tipo_id';

	/**
	 * insert recibe la informacion de un Tipo reserva y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$reserva_tipo_nombre = $data['reserva_tipo_nombre'];
		$query = "INSERT INTO reserva_tipo( reserva_tipo_nombre) VALUES ( '$reserva_tipo_nombre')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un Tipo reserva  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$reserva_tipo_nombre = $data['reserva_tipo_nombre'];
		$query = "UPDATE reserva_tipo SET  reserva_tipo_nombre = '$reserva_tipo_nombre' WHERE reserva_tipo_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}