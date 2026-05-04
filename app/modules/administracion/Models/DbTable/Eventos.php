<?php 
/**
* clase que genera la insercion y edicion  de eventos en la base de datos
*/
class Administracion_Model_DbTable_Eventos extends Db_Table
{
	/**
	 * [ nombre de la tabla actual]
	 * @var string
	 */
	protected $_name = 'eventos';

	/**
	 * [ identificador de la tabla actual en la base de datos]
	 * @var string
	 */
	protected $_id = 'evento_id';

	/**
	 * insert recibe la informacion de un evento y la inserta en la base de datos
	 * @param  array Array array con la informacion con la cual se va a realizar la insercion en la base de datos
	 * @return integer      identificador del  registro que se inserto
	 */
	public function insert($data){
		$evento_tipo = $data['evento_tipo'];
		$evento_nombre = $data['evento_nombre'];
		$evento_imagen = $data['evento_imagen'];
		$evento_costo = $data['evento_costo'];
		$evento_fecha = $data['evento_fecha'];
		$evento_hora = $data['evento_hora'];
		$evento_lugar = $data['evento_lugar'];
		$evento_descripcion = $data['evento_descripcion'];
		$evento_titulo_politica = $data['evento_titulo_politica'];
		$evento_descripcion_politica = $data['evento_descripcion_politica'];
		$evento_bono = $data['evento_bono'];
		$evento_activo = $data['evento_activo'];
		$evento_estado = $data['evento_estado'];
		$evento_aforomaximo = $data['evento_aforomaximo'];
		$evento_cupo = $data['evento_cupo'];
		$evento_porcentaje_pagoinicial = $data['evento_porcentaje_pagoinicial'];
		$query = "INSERT INTO eventos( evento_tipo, evento_nombre, evento_imagen, evento_costo, evento_fecha, evento_hora, evento_lugar, evento_descripcion, evento_titulo_politica, evento_descripcion_politica, evento_bono, evento_activo, evento_estado, evento_aforomaximo, evento_porcentaje_pagoinicial, evento_cupo) VALUES ( '$evento_tipo', '$evento_nombre', '$evento_imagen', '$evento_costo', '$evento_fecha', '$evento_hora', '$evento_lugar', '$evento_descripcion', '$evento_titulo_politica', '$evento_descripcion_politica', '$evento_bono', '$evento_activo', '$evento_estado', '$evento_aforomaximo', '$evento_porcentaje_pagoinicial', '$evento_cupo')";
		$res = $this->_conn->query($query);
        return mysqli_insert_id($this->_conn->getConnection());
	}

	/**
	 * update Recibe la informacion de un evento  y actualiza la informacion en la base de datos
	 * @param  array Array Array con la informacion con la cual se va a realizar la actualizacion en la base de datos
	 * @param  integer    identificador al cual se le va a realizar la actualizacion
	 * @return void
	 */
	public function update($data,$id){
		
		$evento_tipo = $data['evento_tipo'];
		$evento_nombre = $data['evento_nombre'];
		$evento_imagen = $data['evento_imagen'];
		$evento_costo = $data['evento_costo'];
		$evento_fecha = $data['evento_fecha'];
		$evento_hora = $data['evento_hora'];
		$evento_lugar = $data['evento_lugar'];
		$evento_descripcion = $data['evento_descripcion'];
		$evento_titulo_politica = $data['evento_titulo_politica'];
		$evento_descripcion_politica = $data['evento_descripcion_politica'];
		$evento_bono = $data['evento_bono'];
		$evento_activo = $data['evento_activo'];
		$evento_estado = $data['evento_estado'];
		$evento_aforomaximo = $data['evento_aforomaximo'];
		$evento_porcentaje_pagoinicial = $data['evento_porcentaje_pagoinicial'];
		$evento_cupo = $data['evento_cupo'];
		$query = "UPDATE eventos SET  evento_tipo = '$evento_tipo', evento_nombre = '$evento_nombre', evento_imagen = '$evento_imagen', evento_costo = '$evento_costo', evento_fecha = '$evento_fecha', evento_hora = '$evento_hora', evento_lugar = '$evento_lugar', evento_descripcion = '$evento_descripcion', evento_titulo_politica = '$evento_titulo_politica', evento_descripcion_politica = '$evento_descripcion_politica', evento_bono = '$evento_bono', evento_activo = '$evento_activo', evento_estado = '$evento_estado', evento_aforomaximo = '$evento_aforomaximo', evento_porcentaje_pagoinicial = '$evento_porcentaje_pagoinicial', evento_cupo = '$evento_cupo' WHERE evento_id = '".$id."'";
		$res = $this->_conn->query($query);
	}
}