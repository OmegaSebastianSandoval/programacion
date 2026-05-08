<?php 
/**
* clase que genera la insercion y edicion  de Reservas evento en la base de datos
*/
class Administracion_Model_DbTable_Reservaevento extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'reserva_evento';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'reserva_evento_id';

	/**
	 * insert recibe la informacion de un Reserva y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$reserva_evento_evento = $data['reserva_evento_evento'];
		$reserva_evento_tipo = $data['reserva_evento_tipo'];
		$reserva_evento_nombre = $data['reserva_evento_nombre'];
		$reserva_evento_precio = $data['reserva_evento_precio'];
		$reserva_evento_capacidad = $data['reserva_evento_capacidad'];
		$reserva_evento_cantidad = $data['reserva_evento_cantidad'];
		$reserva_evento_cantidad_vendidas = $data['reserva_evento_cantidad_vendidas'];
		$reserva_evento_boleta_req = $data['reserva_evento_boleta_req'];
		$reserva_evento_boletas_x_reserva = $data['reserva_evento_boletas_x_reserva'];
		$reserva_evento_fechalimite = $data['reserva_evento_fechalimite'];
		$reserva_evento_activo = $data['reserva_evento_activo'];
		$query = "INSERT INTO reserva_evento( reserva_evento_evento, reserva_evento_tipo, reserva_evento_nombre, reserva_evento_precio, reserva_evento_capacidad, reserva_evento_cantidad, reserva_evento_cantidad_vendidas, reserva_evento_boleta_req, reserva_evento_boletas_x_reserva, reserva_evento_fechalimite, reserva_evento_activo) VALUES ( '$reserva_evento_evento', '$reserva_evento_tipo', '$reserva_evento_nombre', '$reserva_evento_precio', '$reserva_evento_capacidad', '$reserva_evento_cantidad', '$reserva_evento_cantidad_vendidas', '$reserva_evento_boleta_req', '$reserva_evento_boletas_x_reserva', '$reserva_evento_fechalimite', '$reserva_evento_activo')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un Reserva  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$reserva_evento_evento = $data['reserva_evento_evento'];
		$reserva_evento_tipo = $data['reserva_evento_tipo'];
		$reserva_evento_nombre = $data['reserva_evento_nombre'];
		$reserva_evento_precio = $data['reserva_evento_precio'];
		$reserva_evento_capacidad = $data['reserva_evento_capacidad'];
		$reserva_evento_cantidad = $data['reserva_evento_cantidad'];
		$reserva_evento_cantidad_vendidas = $data['reserva_evento_cantidad_vendidas'];
		$reserva_evento_boleta_req = $data['reserva_evento_boleta_req'];
		$reserva_evento_boletas_x_reserva = $data['reserva_evento_boletas_x_reserva'];
		$reserva_evento_fechalimite = $data['reserva_evento_fechalimite'];
		$reserva_evento_activo = $data['reserva_evento_activo'];
		$query = "UPDATE reserva_evento SET  reserva_evento_evento = '$reserva_evento_evento', reserva_evento_tipo = '$reserva_evento_tipo', reserva_evento_nombre = '$reserva_evento_nombre', reserva_evento_precio = '$reserva_evento_precio', reserva_evento_capacidad = '$reserva_evento_capacidad', reserva_evento_cantidad = '$reserva_evento_cantidad', reserva_evento_cantidad_vendidas = '$reserva_evento_cantidad_vendidas', reserva_evento_boleta_req = '$reserva_evento_boleta_req', reserva_evento_boletas_x_reserva = '$reserva_evento_boletas_x_reserva', reserva_evento_fechalimite = '$reserva_evento_fechalimite', reserva_evento_activo = '$reserva_evento_activo' WHERE reserva_evento_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}