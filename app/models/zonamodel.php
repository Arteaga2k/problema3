<?php

/**
 * CLASE zonamodel
 * @author 2DAWT
 *
 */
class ZonaModel
{

    /**
     *
     * @var unknown
     */
    private $table = "tbl_zona";

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        require_once 'app/libs/mysql_DB.php';
        $this->mysqlDB = new MysqlDB();
    }

    /**
     * Pide a la base de datos el listado de zonas
     *
     * @return multitype:
     */
    public function getZonas()
    {
        $result = $this->mysqlDB->select()
            ->from($this->table)
            ->fetchAll();
        
        return $result;
    }

    /**
     * Devuelve la información de una zona determinada por su id
     * 
     * @param unknown $id_zona
     * @return multitype:
     */
    public function getZona($id_zona)
    {
        $binds = array(
            ':id_zona' => $id_zona
        );
        
        $this->mysqlDB->setBinds($binds);
        
        return $result = $this->mysqlDB->select()
            ->from($this->table)
            ->where('id_zona')
            ->fetch();
    }

    /**
     */
    public function addZona($dataForm)
    {
        // bindeamos parametros
        foreach ($dataForm as $key => $value) {
            $binds[":$key"] = $value; // iria en el execute
        }
        // var_dump($dataForm);
        $this->mysqlDB->setBinds($binds);
        $this->mysqlDB->insert($this->table, $dataForm);
    }
    
    /**
     * Elimina un envio determinado por su id en la base de datos
     * @param unknown $id_zona
     */
    public function deleteZona($id_zona)
    {
        try {
            $this->mysqlDB->setBinds(array(
                ':id_zona' => $id_zona
            ));
            
            $this->mysqlDB->where('id_zona')->delete($this->table);            
           return TRUE;
           // al ser foreign key en tabla envio puede dar error al intentar eliminar
        } catch (Exception $e) {
           return FALSE;
        }
       
    }

    /**
     * Edita datos de zona determinado por su id  en la base de datos
     * @param unknown $dataForm
     * @param unknown $id_zona
     */
    public function editZona($dataForm, $id_zona)
    {
        // bindeamos campos del formulario, que coinciden con la tabla envio
        foreach ($dataForm as $key => $value) {
            $binds[":$key"] = $value;
        }
        // id_envio no está como campo de formulario, lo añadimos
        $binds[":id_zona"] = $id_zona;
        
        $this->mysqlDB->setBinds($binds);
        $this->mysqlDB->where('id_zona')->update($this->table, $dataForm);
    }

   
}