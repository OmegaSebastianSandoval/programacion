<?php
/**
 * clase que genera la insercion y edicion  de Tickets en la base de datos
 */
class Administracion_Model_DbTable_Tickets extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'tickets';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'ticket_id';

	/**
	 * insert recibe la informacion de un Ticket y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data)
	{
		$ticket_compra_id              = $data['ticket_compra_id'] ?? '';
		$ticket_evento_id              = $data['ticket_evento_id'] ?? '';
		$ticket_numero_ticket          = $data['ticket_numero_ticket'] ?? '';
		$ticket_uid                    = $data['ticket_uid'] ?? '';
		$ticket_token                  = $data['ticket_token'] ?? '';
		$ticket_estado                 = $data['ticket_estado'] ?? 1;
		$ticket_tipo                   = $data['ticket_tipo'] ?? '';
		$ticket_fecha_creacion         = $data['ticket_fecha_creacion'] ?? date('Y-m-d H:i:s');
		$ticket_fecha_expiracion       = $data['ticket_fecha_expiracion'] ?? '';
		$ticket_metodo_validacion      = $data['ticket_metodo_validacion'] ?? '';
		$ticket_dispositivo_validacion = $data['ticket_dispositivo_validacion'] ?? '';
		$ticket_ip_validacion          = $data['ticket_ip_validacion'] ?? '';
		$ticket_observaciones          = $data['ticket_observaciones'] ?? '';
		$ticket_usuario_validador      = $data['ticket_usuario_validador'] ?? '';

		$fecha_validacion_sql = !empty($data['ticket_fecha_validacion'])
			? "'" . $data['ticket_fecha_validacion'] . "'"
			: 'NULL';

		$query = "INSERT INTO tickets(
			ticket_compra_id, ticket_evento_id, ticket_numero_ticket, ticket_uid, ticket_token,
			ticket_estado, ticket_tipo, ticket_fecha_creacion, ticket_fecha_validacion,
			ticket_metodo_validacion, ticket_dispositivo_validacion, ticket_ip_validacion,
			ticket_fecha_expiracion, ticket_observaciones, ticket_usuario_validador
		) VALUES (
			'$ticket_compra_id', '$ticket_evento_id', '$ticket_numero_ticket', '$ticket_uid', '$ticket_token',
			'$ticket_estado', '$ticket_tipo', '$ticket_fecha_creacion', $fecha_validacion_sql,
			'$ticket_metodo_validacion', '$ticket_dispositivo_validacion', '$ticket_ip_validacion',
			'$ticket_fecha_expiracion', '$ticket_observaciones', '$ticket_usuario_validador'
		)";
		$this->_conn->query($query);
		return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un Ticket  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data, $id)
	{

		$ticket_compra_id = $data['ticket_compra_id'];
		$ticket_evento_id = $data['ticket_evento_id'];
		$ticket_numero_ticket = $data['ticket_numero_ticket'];
		$ticket_uid = $data['ticket_uid'];
		$ticket_token = $data['ticket_token'];
		$ticket_estado = $data['ticket_estado'];
		$ticket_fecha_creacion = $data['ticket_fecha_creacion'];
		$ticket_fecha_validacion = $data['ticket_fecha_validacion'];
		$ticket_metodo_validacion = $data['ticket_metodo_validacion'];
		$ticket_dispositivo_validacion = $data['ticket_dispositivo_validacion'];
		$ticket_ip_validacion = $data['ticket_ip_validacion'];
		$ticket_fecha_expiracion = $data['ticket_fecha_expiracion'];
		$ticket_observaciones = $data['ticket_observaciones'];
		$ticket_usuario_validador = $data['ticket_usuario_validador'];
		$query = "UPDATE tickets SET  ticket_compra_id = '$ticket_compra_id', ticket_evento_id = '$ticket_evento_id', ticket_numero_ticket = '$ticket_numero_ticket', ticket_uid = '$ticket_uid', ticket_token = '$ticket_token', ticket_estado = '$ticket_estado', ticket_fecha_creacion = '$ticket_fecha_creacion', ticket_fecha_validacion = '$ticket_fecha_validacion', ticket_metodo_validacion = '$ticket_metodo_validacion', ticket_dispositivo_validacion = '$ticket_dispositivo_validacion', ticket_ip_validacion = '$ticket_ip_validacion', ticket_fecha_expiracion = '$ticket_fecha_expiracion', ticket_observaciones = '$ticket_observaciones', ticket_usuario_validador = '$ticket_usuario_validador' WHERE ticket_id = '" . $id . "'";
		$res = $this->_conn->query($query);
	}

	public function getNextTicketId()
	{
		$sql = "SELECT COALESCE(MAX(ticket_id), 0) + 1 AS next_id FROM tickets";
		$result = $this->_conn->query($sql)->fetchAsObject();
		return $result[0]->next_id;
	}
}