<?php 
/**
* clase que genera la insercion y edicion  de Reservas en la base de datos
*/
class Administracion_Model_DbTable_Reservas extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'reservas';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'reserva_id';

	/**
	 * insert recibe la informacion de un Reserva y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$reserva_compra_id = $data['reserva_compra_id'];
		$reserva_evento_id_fk = $data['reserva_evento_id_fk'];
		$reserva_evento = $data['reserva_evento'];
		$reserva_tipo_origen = $data['reserva_tipo_origen'];
		$reserva_cantidad_personas = $data['reserva_cantidad_personas'];
		$reserva_total = $data['reserva_total'];
		$reserva_estado = $data['reserva_estado'];
		$reserva_nombre = $data['reserva_nombre'];
		$reserva_email = $data['reserva_email'];
		$reserva_fecha_creacion = $data['reserva_fecha_creacion'];
		$reserva_notas = $data['reserva_notas'];
		$query = "INSERT INTO reservas( reserva_compra_id, reserva_evento_id_fk, reserva_evento, reserva_tipo_origen, reserva_cantidad_personas, reserva_total, reserva_estado, reserva_nombre, reserva_email, reserva_fecha_creacion, reserva_notas) VALUES ( '$reserva_compra_id', '$reserva_evento_id_fk', '$reserva_evento', '$reserva_tipo_origen', '$reserva_cantidad_personas', '$reserva_total', '$reserva_estado', '$reserva_nombre', '$reserva_email', '$reserva_fecha_creacion', '$reserva_notas')";
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

		$reserva_compra_id = $data['reserva_compra_id'];
		$reserva_evento_id_fk = $data['reserva_evento_id_fk'];
		$reserva_evento = $data['reserva_evento'];
		$reserva_tipo_origen = $data['reserva_tipo_origen'];
		$reserva_cantidad_personas = $data['reserva_cantidad_personas'];
		$reserva_total = $data['reserva_total'];
		$reserva_estado = $data['reserva_estado'];
		$reserva_nombre = $data['reserva_nombre'];
		$reserva_email = $data['reserva_email'];
		$reserva_fecha_creacion = $data['reserva_fecha_creacion'];
		$reserva_notas = $data['reserva_notas'];
		$query = "UPDATE reservas SET  reserva_compra_id = '$reserva_compra_id', reserva_evento_id_fk = '$reserva_evento_id_fk', reserva_evento = '$reserva_evento', reserva_tipo_origen = '$reserva_tipo_origen', reserva_cantidad_personas = '$reserva_cantidad_personas', reserva_total = '$reserva_total', reserva_estado = '$reserva_estado', reserva_nombre = '$reserva_nombre', reserva_email = '$reserva_email', reserva_fecha_creacion = '$reserva_fecha_creacion', reserva_notas = '$reserva_notas' WHERE reserva_id = '".$id."'";
		$res = $this->_conn->query($query);
	}

	public function getByCompraId($compraId) {
		$res = $this->_conn->query("SELECT * FROM reservas WHERE reserva_compra_id = '$compraId' LIMIT 1")->fetchAsObject();
		return $res[0] ?? false;
	}
}