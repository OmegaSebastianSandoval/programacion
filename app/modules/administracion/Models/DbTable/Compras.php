<?php
/**
 * clase que genera la insercion y edicion  de Compras en la base de datos
 */
class Administracion_Model_DbTable_Compras extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'boleta_compra';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'boleta_compra_id';

	/**
	 * insert recibe la informacion de un Compra y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$boleta_compra_evento = $data['boleta_compra_evento'] ?? '';
		$boleta_compra_documento = $data['boleta_compra_documento'] ?? '';
		$boleta_compra_nombre = $data['boleta_compra_nombre'] ?? '';
		$boleta_compra_telefono = $data['boleta_compra_telefono'] ?? '';
		$boleta_compra_email = $data['boleta_compra_email'] ?? '';
		$boleta_compra_fechacedula = $data['boleta_compra_fechacedula'] ?? '';
		$boleta_compra_fechanacimiento = $data['boleta_compra_fechanacimiento'] ?? '';
		$boleta_compra_fecha = $data['boleta_compra_fecha'] ?? date('Y-m-d H:i:s');
		$boleta_compra_codigo = $data['boleta_compra_codigo'] ?? '';
		$boleta_compra_respuesta = $data['boleta_compra_respuesta'] ?? '';
		$boleta_compra_validacion = $data['boleta_compra_validacion'] ?? '';
		$boleta_compra_validacion2 = $data['boleta_compra_validacion2'] ?? '';
		$boleta_compra_entidad = $data['boleta_compra_entidad'] ?? '';
		$boleta_compra_total = $data['boleta_compra_total'] ?? 0;
		$boleta_compra_vendedor = $data['boleta_compra_vendedor'] ?? '';
		$boleta_compra_validacionentrada = $data['boleta_compra_validacionentrada'] ?? '';
		$boleta_compra_raw = $data['boleta_compra_raw'] ?? '';
		$fechavalidacion_sql = !empty($data['boleta_compra_fechavalidacion'])
			? "'" . $data['boleta_compra_fechavalidacion'] . "'"
			: 'NULL';
		$query = "INSERT INTO boleta_compra(boleta_compra_evento, boleta_compra_documento, boleta_compra_nombre, boleta_compra_telefono, boleta_compra_email, boleta_compra_fechacedula, boleta_compra_fechanacimiento, boleta_compra_fecha, boleta_compra_codigo, boleta_compra_respuesta, boleta_compra_validacion, boleta_compra_validacion2, boleta_compra_entidad, boleta_compra_total, boleta_compra_vendedor, boleta_compra_validacionentrada, boleta_compra_fechavalidacion, boleta_compra_raw) VALUES (  '$boleta_compra_evento', '$boleta_compra_documento', '$boleta_compra_nombre', '$boleta_compra_telefono', '$boleta_compra_email', '$boleta_compra_fechacedula', '$boleta_compra_fechanacimiento', '$boleta_compra_fecha', '$boleta_compra_codigo', '$boleta_compra_respuesta', '$boleta_compra_validacion', '$boleta_compra_validacion2', '$boleta_compra_entidad', '$boleta_compra_total', '$boleta_compra_vendedor', '$boleta_compra_validacionentrada', $fechavalidacion_sql, '$boleta_compra_raw')";
		$res = $this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un Compra  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{
		$boleta_compra_evento = $data['boleta_compra_evento'];
		$boleta_compra_documento = $data['boleta_compra_documento'];
		$boleta_compra_nombre = $data['boleta_compra_nombre'];
		$boleta_compra_telefono = $data['boleta_compra_telefono'];
		$boleta_compra_email = $data['boleta_compra_email'];
		$boleta_compra_fechacedula = $data['boleta_compra_fechacedula'];
		$boleta_compra_fechanacimiento = $data['boleta_compra_fechanacimiento'];
		$boleta_compra_fecha = $data['boleta_compra_fecha'];
		$boleta_compra_codigo = $data['boleta_compra_codigo'];
		$boleta_compra_respuesta = $data['boleta_compra_respuesta'];
		$boleta_compra_validacion = $data['boleta_compra_validacion'];
		$boleta_compra_validacion2 = $data['boleta_compra_validacion2'];
		$boleta_compra_entidad = $data['boleta_compra_entidad'];
		$boleta_compra_total = $data['boleta_compra_total'];
		$boleta_compra_vendedor = $data['boleta_compra_vendedor'];
		$boleta_compra_validacionentrada = $data['boleta_compra_validacionentrada'];
		$boleta_compra_fechavalidacion = $data['boleta_compra_fechavalidacion'];
		$boleta_compra_raw = $data['boleta_compra_raw'];
		$query = "UPDATE boleta_compra SET boleta_compra_evento =  '$boleta_compra_evento',boleta_compra_documento = '$boleta_compra_documento', boleta_compra_nombre = '$boleta_compra_nombre', boleta_compra_telefono = '$boleta_compra_telefono', boleta_compra_email = '$boleta_compra_email', boleta_compra_fechacedula = '$boleta_compra_fechacedula', boleta_compra_fechanacimiento = '$boleta_compra_fechanacimiento', boleta_compra_fecha = '$boleta_compra_fecha', boleta_compra_codigo = '$boleta_compra_codigo', boleta_compra_respuesta = '$boleta_compra_respuesta', boleta_compra_validacion = '$boleta_compra_validacion', boleta_compra_validacion2 = '$boleta_compra_validacion2', boleta_compra_entidad = '$boleta_compra_entidad', boleta_compra_total = '$boleta_compra_total', boleta_compra_vendedor = '$boleta_compra_vendedor', boleta_compra_validacionentrada = '$boleta_compra_validacionentrada', boleta_compra_fechavalidacion = '$boleta_compra_fechavalidacion', boleta_compra_raw = '$boleta_compra_raw' WHERE boleta_compra_id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}
	public function updateConfirmacion($respuesta, $estado, $estadoTx, $id, $franquicia)
	{
		if ($id != "") {
			$query = "UPDATE boleta_compra SET  boleta_compra_respuesta = '$respuesta', boleta_compra_validacion='$estado', boleta_compra_validacion2='$estadoTx', boleta_compra_entidad = '$franquicia' WHERE boleta_compra_id = '$id' ";
			$res = $this->_conn->query($query);
			return mysqli_insert_id($this->_conn->getConnection());
		}
	}

	public function getVentaInfo($id)
	{
		$stmt = "
       
    ";
		$res = $this->_conn->query($stmt)->fetchAsObject();
		return $res[0];
	}
}