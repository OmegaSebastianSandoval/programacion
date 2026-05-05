<?php 
/**
* clase que genera la insercion y edicion  de Detalles en la base de datos
*/
class Administracion_Model_DbTable_Compradetalle extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'boleta_compra_detalle';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'detalle_id';

	/**
	 * insert recibe la informacion de un detalle y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$detalle_compra = $data['detalle_compra'];
		$detalle_boleta = $data['detalle_boleta'];
		$detalle_cantidad = $data['detalle_cantidad'];
		$detalle_precio_unit = $data['detalle_precio_unit'];
		$detalle_precio_reserva = $data['detalle_precio_reserva'];
		$detalle_subtotal = $data['detalle_subtotal'];
		$query = "INSERT INTO boleta_compra_detalle( detalle_compra, detalle_boleta, detalle_cantidad, detalle_precio_unit, detalle_precio_reserva, detalle_subtotal) VALUES ( '$detalle_compra', '$detalle_boleta', '$detalle_cantidad', '$detalle_precio_unit', '$detalle_precio_reserva', '$detalle_subtotal')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un detalle  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$detalle_compra = $data['detalle_compra'];
		$detalle_boleta = $data['detalle_boleta'];
		$detalle_cantidad = $data['detalle_cantidad'];
		$detalle_precio_unit = $data['detalle_precio_unit'];
		$detalle_precio_reserva = $data['detalle_precio_reserva'];
		$detalle_subtotal = $data['detalle_subtotal'];
		$query = "UPDATE boleta_compra_detalle SET  detalle_compra = '$detalle_compra', detalle_boleta = '$detalle_boleta', detalle_cantidad = '$detalle_cantidad', detalle_precio_unit = '$detalle_precio_unit', detalle_precio_reserva = '$detalle_precio_reserva', detalle_subtotal = '$detalle_subtotal' WHERE detalle_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}