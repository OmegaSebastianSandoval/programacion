<?php
/**
 * clase que genera la insercion y edicion  de Boletas evento en la base de datos
 */
class Administracion_Model_DbTable_Boletaevento extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'boleta_evento';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'boleta_evento_id';

	/**
	 * insert recibe la informacion de un Boleta evento y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$boleta_evento_tipo = $data['boleta_evento_tipo'];
		$boleta_evento_cantidad = $data['boleta_evento_cantidad'];
		$boleta_evento_saldo = $data['boleta_evento_saldo'];
		$boleta_evento_evento = $data['boleta_evento_evento'];
		$boleta_evento_precio = $data['boleta_evento_precio'];
		$boleta_evento_precioreserva = $data['boleta_evento_precioreserva'];
		$boleta_evento_fechalimite = $data['boleta_evento_fechalimite'];
		$boleta_evento_horalimite = $data['boleta_evento_horalimite'];
		$query = "INSERT INTO boleta_evento( boleta_evento_tipo, boleta_evento_cantidad, boleta_evento_saldo, boleta_evento_evento, boleta_evento_precio, boleta_evento_precioreserva, boleta_evento_fechalimite, boleta_evento_horalimite, boleta_evento_cantidad_vendidas) VALUES ( '$boleta_evento_tipo', '$boleta_evento_cantidad', '$boleta_evento_saldo', '$boleta_evento_evento', '$boleta_evento_precio', '$boleta_evento_precioreserva', '$boleta_evento_fechalimite', '$boleta_evento_horalimite', '0')";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un Boleta evento  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{

		$boleta_evento_tipo = $data['boleta_evento_tipo'];
		$boleta_evento_cantidad = $data['boleta_evento_cantidad'];
		$boleta_evento_saldo = $data['boleta_evento_saldo'];
		$boleta_evento_evento = $data['boleta_evento_evento'];
		$boleta_evento_precio = $data['boleta_evento_precio'];
		$boleta_evento_precioreserva = $data['boleta_evento_precioreserva'];
		$boleta_evento_fechalimite = $data['boleta_evento_fechalimite'];
		$boleta_evento_horalimite = $data['boleta_evento_horalimite'];
		$query = "UPDATE boleta_evento SET  boleta_evento_tipo = '$boleta_evento_tipo', boleta_evento_cantidad = '$boleta_evento_cantidad', boleta_evento_saldo = '$boleta_evento_saldo', boleta_evento_evento = '$boleta_evento_evento', boleta_evento_precio = '$boleta_evento_precio', boleta_evento_precioreserva = '$boleta_evento_precioreserva', boleta_evento_fechalimite = '$boleta_evento_fechalimite', boleta_evento_horalimite = '$boleta_evento_horalimite' WHERE boleta_evento_id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}
}