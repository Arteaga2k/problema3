<?php

/**
 * Clase enviosmodel
 * 
 * 
 * 
 * @author Carlos
 *
 */
class EnviosModel
{

    /**
     * nombre de la tabla envio
     *
     * @var unknown
     */
    private $table = "tbl_envio";

    /**
     * array valores a bindear
     *
     * @var unknown
     */
    private $binds = array();

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        require_once 'app/libs/mysql_DB.php';
        $this->mysqlDB = new MysqlDB();
    }

    /**
     * Devuelve resultado consulta todas las filas de la tabla tbl_envio
     *
     * @param unknown $id_zona            
     * @return multitype:
     */
    public function getEnvios($id_zona)
    {
        $this->binds[':zona_recepcion'] = $id_zona;
        $this->mysqlDB->setBinds($this->binds);
        $result = $this->mysqlDB->select()
            ->where('zona_recepcion')
            ->from($this->table)
            ->fetchAll();
        
        unset($this->binds);
        return $result;
    }

    /**
     * Obtener todos los envios de la tabla Envios
     */
    public function getPagEnvios($filtro = NULL, $offset = 0, $count = NULL, $id_zona)
    {
        if (is_null($filtro) || empty($filtro)) {
            
            $this->binds[':zona_recepcion'] = $id_zona;
            
            $this->mysqlDB->setBinds($this->binds);
            
            $result = $this->mysqlDB->select()
                ->limit("$offset," . session::get('REGS_PAG'))
                ->where('zona_recepcion')
                ->from($this->table)
                ->fetchAll();
        } else {
            $this->binds['zona_recepcion'] = $id_zona;
            $this->setFiltros($filtro, $id_zona);
            $this->mysqlDB->select()
                ->limit("$offset," . session::get('REGS_PAG'))
                ->from($this->table)
                ->
            // ->where('zona_recepcion')
            orderBy('fec_creacion', 'desc');
            $result = $this->mysqlDB->fetchAll();
        }
        unset($this->binds);
        return $result;
    }

    /**
     * Guarda y bindea campos de busqueda
     */
    public function setFiltros($filtro, $id_zona)
    {
        
        // existe filtro campo texto
        if (! empty($filtro['filtro_texto'])) {
            $this->binds['direccion'] = '%' . $filtro['filtro_texto'] . '%';
            $this->binds['poblacion'] = '%' . $filtro['filtro_texto'] . '%';
            $this->binds['nombre'] = '%' . $filtro['filtro_texto'] . '%';
            $this->binds['apellido1'] = '%' . $filtro['filtro_texto'] . '%';
            $this->binds['apellido2'] = '%' . $filtro['filtro_texto'] . '%';
            $this->binds['razonsocial'] = '%' . $filtro['filtro_texto'] . '%';
            
            foreach ($this->binds as $key => $value) {
                if ($key != 'zona_recepcion')
                    $fields[$key] = 'like';
            }
            
            $this->mysqlDB->or_where($fields);
        }
        // existe filtro fecha desde
        if (! empty($filtro['filtro_fec_desde'])) {
            $this->binds['fec_creacion'] = $filtro['filtro_fec_desde'];
            
            $this->mysqlDB->or_where(array(
                'fec_creacion' => '>='
            ));
        }
        
        // si existe filtro fec hasta
        if (! empty($filtro['filtro_fec_hasta'])) {
            $this->binds['fec_creacion'] = $filtro['filtro_fec_hasta'];
            
            $this->mysqlDB->where(array(
                'fec_creacion' => '<='
            ));
        }
        $this->mysqlDB->where('zona_recepcion');
        
        $this->mysqlDB->setBinds($this->binds);
    }

    /**
     * Pide a la base de datos el listado de provincia
     *
     * @return array $result Listado de provincias
     */
    public function getAllProvincias()
    {
        $result = $this->mysqlDB->select()
            ->from('tbl_provincia')
            ->fetchAll();
        
        return $result;
    }

    /**
     *
     * @param string $id_envio
     *            identificador de la tabla
     * @return mixed
     */
    public function getEnvio($id_envio, $id_zona)
    {
        $this->binds['id_envio'] = $id_envio;
        $this->binds['zona_recepcion'] = $id_zona;
        
        $this->mysqlDB->setBinds($this->binds);
        
        $result = $this->mysqlDB->where('id_envio')
            ->where('zona_recepcion')
            ->select()
            ->from($this->table)
            ->fetch();
        unset($this->binds);
        return $result;
    }

    /**
     * Añade envio en la base de datos
     *
     * @param unknown $dataForm            
     */
    public function addEnvio($dataForm)
    {
        
        // bindeamos parametros
        foreach ($dataForm as $key => $value) {
            $this->binds[":$key"] = $value;
        }
        
        $this->mysqlDB->setBinds($this->binds);
        $this->mysqlDB->insert($this->table, $dataForm);
        unset($this->binds);
    }

    /**
     * Edita datos de envío determinado por su id en la base de datos
     *
     * @param unknown $dataForm            
     * @param unknown $id_envio            
     */
    public function editEnvio($dataForm, $id_envio)
    {
        // bindeamos campos del formulario, que coinciden con la tabla envio
        foreach ($dataForm as $key => $value) {
            $this->binds[":$key"] = $value;
        }
        // id_envio no está como campo de formulario, lo añadimos
        $this->binds["id_envio"] = $id_envio;
        
        $this->mysqlDB->setBinds($this->binds);
        $this->mysqlDB->where('id_envio')->update($this->table, $dataForm);
        
        unset($this->binds);
    }

    /**
     * Edita estado de un envio determinado por su id en la base de datos
     *
     * @param unknown $dataForm            
     * @param unknown $id_envio            
     */
    public function anotaEnvio($dataForm, $id_envio)
    {
        $dataForm['id_envio'] = 'id_envio';
        $dataForm['estado'] = 'estado';
        
        // bindeamos campos del formulario, que coinciden con la tabla envio
        foreach ($dataForm as $key => $value) {
            $binds[":$key"] = $value;
        }
        // id_envio no está como campo de formulario, lo añadimos
        $binds[":id_envio"] = $id_envio;
        $binds[":estado"] = 'e';
        
        $this->mysqlDB->setBinds($binds);
        $this->mysqlDB->where('id_envio')->update($this->table, $dataForm);
        
        unset($this->binds);
    }

    /**
     * Elimina un envio determinado por su id en la base de datos
     *
     * @param unknown $id_envio            
     */
    public function deleteEnvio($id_envio)
    {
        try {
            $this->mysqlDB->setBinds(array(
                ':id_envio' => $id_envio
            ));
            
            $this->mysqlDB->where('id_envio')->delete($this->table);
            unset($this->binds);
            
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    /**
     * Obtenemos el numero de filas total de una consulta
     *
     * @return Ambigous <>
     */
    public function getTotalRows($filtro, $id_zona)
    {
        if (is_null($filtro) || empty($filtro)) {
            $this->binds[':zona_recepcion'] = $id_zona;
            $this->mysqlDB->setBinds($this->binds);
            $result = $this->mysqlDB->select("COUNT(*) as total")
                ->where('zona_recepcion')
                ->from($this->table)
                ->fetch();
        } else {
            $this->binds['zona_recepcion'] = $id_zona;
            $this->setFiltros($filtro, $id_zona);
            $result = $this->mysqlDB->select("COUNT(*) as total")
                ->from($this->table)
                ->fetch();
        }
        unset($this->binds);
        return $result['total'];
    }

    /**
     * Prepara los datos de envíos para mostrarlos en una gráfica
     * 
     * @return multitype:NULL multitype:
     */
    public function pieChart()
    {
        $data = array();
        
        $this->binds[':estado'] = 'e';
        $this->mysqlDB->setBinds($this->binds);
        $data['enviados'] = $this->mysqlDB->select("COUNT(*) as total")
            ->where('estado')
            ->from($this->table)
            ->fetch();
        unset($this->binds);
        
        $this->binds[':estado'] = 'p';
        $this->mysqlDB->setBinds($this->binds);
        $data['pendientes'] = $this->mysqlDB->select("COUNT(*) as total")
            ->where('estado')
            ->from($this->table)
            ->fetch();
        unset($this->binds);
        
        $this->binds[':estado'] = 'd';
        $this->mysqlDB->setBinds($this->binds);
        $data['devueltos'] = $this->mysqlDB->select("COUNT(*) as total")
            ->where('estado')
            ->from($this->table)
            ->fetch();
        unset($this->binds);
        $table = array();
        $table['cols'] = array(
        
            // Labels for your chart, these represent the column titles
            // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
            array('label' => 'envios','pattern' => "", 'type' => 'string'),
            array('label' => 'total', 'pattern' => "", 'type' => 'number')
        
        );
        
        
        $temp = array();
        // the following line will be used to slice the Pie chart
        $enviados[] = array('v' => 'enviados','f' =>NULL);  
        $enviados[] = array('v' => (int) $data['enviados']['total'],'f' =>NULL);
        $rows[] = array('c' => $enviados);
        
        $pendientes[] = array('v' => 'pendientes','f' =>NULL);
        $pendientes[] = array('v' => (int) $data['pendientes']['total'],'f' =>NULL);
        $rows[] = array('c' => $pendientes);
        
        $devueltos[] = array('v' => 'devueltos','f' =>NULL);
        $devueltos[] = array('v' => (int) $data['devueltos']['total'],'f' =>NULL);
        $rows[] = array('c' => $devueltos);
        
        $table['rows'] = $rows;        
        $jsonTable = json_encode($table);
        
        return $jsonTable;
    }
}