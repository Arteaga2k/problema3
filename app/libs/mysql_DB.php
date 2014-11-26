<?php

/** 
 * Clase mysqldb 
 * 
 * Intenta emular el comportamiento de active record de codeigniter, prepara el query
 * 
 * @author Carlos
 */
class MysqlDB
{

    /**
     *
     * @var array datos enlazados
     */
    private $binds;

    /**
     *
     * @var conexion PDO
     */
    private $db;

    /**
     *
     * @var sentencia preparada PDO
     */
    private $sth;

    /**
     *
     * @var string cadena Select del query
     */
    private $select = null;

    /**
     *
     * @var string nombre campos del query
     */
    private $fields = array();

    /**
     *
     * @var string nombre de la Tabla
     */
    private $table = null;

    /**
     *
     * @var string cadena Where del query
     */
    private $where = null;

    /**
     *
     * @var string cadena orderby del query
     */
    private $orderBy = null;

    /**
     *
     * @var string cadena groupby del query
     */
    private $groupBy = null;

    /**
     *
     * @var string cadena limit del query
     */
    private $limit = null;

    /**
     *
     * @var unknown
     */
    private $ar_where = null;

    /**
     * Constructor de la clase
     */
    public function __construct()
    {
        require_once 'app/libs/db.php';
        $this->db = Db::singleton();
    }

    /**
     * Guarda el array de datos enlazados
     *
     * @param array $arrayBinds
     *            Datos enlazados
     */
    public function setBinds($arrayBinds)
    {
        $this->binds = $arrayBinds;
    }

    /**
     * Método que construye el sql de una sentencia SELECT
     *
     * @return sentencia sql
     */
    public function buildSql()
    {
        $sql = $this->select;
        $sql .= $this->fields;
        
        $sql .= $this->table;
        
        if (! is_null($this->where)) {
            $this->where .= ")";
            $sql .= $this->where;
        }
        
        if (! is_null($this->groupBy)) {
            $sql .= $this->groupBy;
        }
        
        if (! is_null($this->orderBy)) {
            $sql .= $this->orderBy;
        }
        if (! is_null($this->limit)) {
            $sql .= $this->limit;
        }
        // $sql;
        $this->_destruct();
        
        return $sql;
    }

    /**
     *
     * @param string $table
     *            Nombre de la tabla
     * @param array $values
     *            Array con los campos de la tabla
     * @return boolean Resultado de la ejecución PDO
     */
    public function insert($table, $values)
    {
        $this->table = $table;
        
        $campos = implode(", ", array_keys($values));
        
        var_dump($campos);
        
        $sql = "INSERT INTO   $table   ( $campos ) VALUES (";
        
        foreach ($values as $key => $value) {
            $sql .= ":$key,";
        }
        $sql = substr($sql, 0, - 1);
        
        $sql .= " )";
        
        $this->sth = $this->db->prepare($sql);
        return $result = $this->sth->execute($this->binds);
    }

    /**
     *
     * @param string $table
     *            nombre de la tabla
     * @param array $values
     *            array con los campos de la tabla
     * @param string $condition
     *            la id de la fila a actualizar
     * @return boolean Resultado de la ejecución PDO
     */
    public function update($table, $values)
    {
        $this->table = $table;
        
        $sql = "UPDATE " . $table . " SET";
        
        foreach ($values as $key => $value) {
            $sql .= " $key = :$key,";
        }
        $sql = substr($sql, 0, - 1);
        
        if ($this->where != null) {
            $sql .= $this->where .= ")";
        }
        var_dump($sql);
        $this->sth = $this->db->prepare($sql);
        return $result = $this->sth->execute($this->binds);
    }

    /**
     *
     * @param string $table
     *            Nombre de la tabla
     * @param string $conditions
     *            la id de la fila a eliminar
     * @return boolean Resultado de la ejecución PDO
     */
    public function delete($table)
    {
        $sql = "DELETE FROM " . $table;
        
        if ($this->where != null) {
            $sql .= $this->where .= ")";
        }
        
        var_dump($sql);
        $this->sth = $this->db->prepare($sql);
        return $result = $this->sth->execute($this->binds);
    }

    /**
     *
     * @param string $fields            
     * @return MysqlDB
     */
    public function select($fields = '*')
    {
        if (is_null($this->select))
            $this->select = 'SELECT ';
        
        if (is_array($fields)) {
            $fieldString = implode(", ", $fields);
            $this->fields = $fieldString;
        } else {
            $this->fields = $fields;
        }
        
        return $this;
    }

    /**
     *
     * @param string $table
     *            nombre de la tabla
     * @param string $alias
     *            nombre de la tabla como alias
     * @return MysqlDB
     */
    public function from($table, $alias = null)
    {
        $this->table = " FROM $table as " . (is_null($alias) ? $table : $alias);
        return $this;
    }

    /**
     * Where
     *
     * Generates the WHERE portion of the query. Separates
     * multiple calls with AND
     *
     * @param
     *            mixed
     * @param
     *            mixed
     * @return object
     */
    public function where($key, $char = NULL)
    {
        return $this->_where($key, $char, 'AND ');
    }

    /**
     * OR Where
     *
     * Genera el WHERE del query. Separa multiples where con OR
     *
     *
     * @param
     *            mixed
     * @param
     *            mixed
     * @return object
     */
    public function or_where($key, $char = NULL)
    {
        return $this->_where($key, $char, 'OR ');
    }

    /**
     * _where
     * 
     * @param unknown $conditions            
     * @param unknown $char            
     * @param string $type            
     * @return MysqlDB
     */
    public function _where($conditions, $char, $type = 'AND ')
    {
        if (is_null($conditions)) {
            return $this;
        }
        
        if (! is_array($conditions))
            $conditions = explode(",", $conditions);
        
        if (is_null($this->where)) {
            $this->where = " WHERE( ";
        }
        
        foreach ($conditions as $key => $value) {
            
            if (! empty($this->ar_where)) {
                $this->where .= " $type ";
            }
            // Si la condición contiene un Like, in, >=...etc lo sustituimos por el '='
            if ($char = $this->esOperador($value)) {
                $this->where .= "$key $char :$key";
                /*
                 * if (strtolower($char) == 'like')
                 * $this->where .= "$key $char :$key";
                 * else
                 * $this->where .= "$key $char :$key";
                 */
            } else {
                $this->where .= "$value = :$value";
            }
            $this->ar_where[] = $this->where;
        }
        
        return $this;
    }

    public function like()
    {}

    /**
     *
     * @param string $string            
     * @return string|boolean
     */
    public function esOperador($cadena)
    {
        if (preg_match("/[<>>=<=!=]{2}|[><]{1}|[likeLIKE]{4}$/", trim($cadena), $match)) {
            return $match[0];
        } else {
            return false;
        }
    }

    /**
     * Devuelve la fila del query
     *
     * @return array fila de la consulta
     */
    public function fetch()
    {
        $this->query();
        return $this->sth->fetch();
    }

    /**
     * Devuelde todas las filas del query
     *
     * @return array filas de la consulta
     */
    public function fetchAll()
    {
        $this->query();
        return $this->sth->fetchAll();
    }

    /**
     * Realizamos un query determinado por la sql construida
     *
     * @param string $sql            
     */
    public function query($sql = null)
    {
        if (is_null($sql))
            $sql = $this->buildSql();
        //var_dump($sql);
        $this->sth = $this->db->prepare($sql);
        if (is_null($this->binds))
            $result = $this->sth->execute();
        else
            $result = $this->sth->execute($this->binds);
    }

    /**
     *
     * @param string|array $orderBy            
     * @return MysqlDB
     */
    public function orderBy($orderBy)
    {
        if (is_array($orderBy)) {
            $this->orderBy = ' ORDER BY ' . implode(',', $orderBy);
        } else {
            
            $this->orderBy = ' ORDER BY ' . $orderBy;
        }
        return $this;
    }

    /**
     *
     * @param string|array $groupBy            
     * @return MysqlDB
     */
    public function groupBy($groupBy)
    {
        if (is_array($groupBy)) {
            $this->groupBy = ' GROUP BY ' . implode(',', $groupBy);
        } else {
            
            $this->groupBy = ' GROUP BY ' . $groupBy;
        }
        return $this;
    }

    /**
     *
     * @param string $limit            
     * @return MysqlDB
     */
    public function limit($limit)
    {
        $this->limit = ' limit ' . $limit;
        return $this;
    }

    /**
     * Vaciamos contenido de las variables
     */
    private function _destruct()
    {
        $this->table = null;
        $this->select = null;
        $this->fields = null;
        $this->where = null;
        $this->ar_where = null;
    }
}