<?php

/**
 * @author Carlos
 * Este ser� el modelo base, que heredar�n el resto de modelos
 * En el constructor llamamos a la clase con patron singlenton db
 */
abstract class Model
{

    /**
     *
     * @var unknown
     */
    protected $_db;

    /**
     *
     * @var unknown
     */
    protected $_sql;

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        require 'app/libs/db.php';
        $this->_db = Db::init();
    }

    /**
     *
     * @param unknown $sql            
     */
    protected function _setSql($sql)
    {
        $this->_sql = $sql;
    }
    
    // metodos abstractos para clases que hereden, ya que no se pueden definir con exactitud
    abstract protected function get($id);

    abstract protected function set();

    abstract protected function edit($id);

    abstract protected function delete($id);

  

    /**
     *
     * @param string $data            
     * @throws Exception
     * @return multitype:
     */
    public function getAll($data = null)
    {
        if (! $this->_sql) {
            throw new Exception("No hay sql");
        }
        
        $sth = $this->_db->prepare($this->_sql);
        $sth->execute($data);
        return $sth->fetchAll();
    }

    /**
     * Ejecuta un query simple del tipo INSERT, DELETE, UPDATE
     *
     * @param array $params
     *            bindParams
     */
    public function singleQuery(array $params = array())
    {
        $sth = $this->_db->prepare($this->_sql);
        $sth->execute($params);
        
        if (empty($params))
            $sth->execute();
        
        $sth->execute($params);
    }

    /**
     *
     * @param string $data            
     * @throws Exception
     * @return mixed
     */
    public function getFila($data = null)
    {
        if (! $this->_sql) {
            throw new Exception("No hay sql");
        }
        
        $sth = $this->_db->prepare($this->_sql);
        $sth->execute($data);
        return $sth->fetch();
    }
}