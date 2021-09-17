<?php

namespace App\Models;

use CodeIgniter\Model;

class ORMModel extends Model
{
    protected $relationsAllowed = [];
    protected $useTimestamps = true;
    protected $user_id = null;

    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    public function beforeInsert(array $data)
    {
        $data['data'][$this->prefixField . '_created_by'] = $this->user_id;
        return $data;
    }

    public function beforeUpdate(array $data)
    {
        if ($this->user_id != null) {
            $data['data'][$this->prefixField . '_updated_by'] = $this->user_id;
        }
        return $data;
    }

    public function __construct($user_id = null)
    { //Cuando se crea manualmente se pierde el user id entonces opcionalmente recibir el parámetro para que al crearlo funcione...
        parent::__construct();
        if ($user_id != null && $this->user_id === null) {
            $this->setUserId($user_id);
        }
        if ($this->prefixField) {
            $this->primaryKey = $this->prefixField . '_PK';
            $this->createdField = $this->prefixField . '_created_at';
            $this->updatedField = $this->prefixField . '_updated_at';
        }
    }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function getSelect($field=''){
        $name = ($field=='')?$this->prefixField.'_name':$field;
        $this->select("$this->primaryKey,$name");
        $data = $this->findAll();
        $response = array();
        foreach($data as $item){
            $response[$item->{$this->primaryKey}] = $item->{$name};
        }
        return $response;
    }

    public function invoke($function, $params = null)
    {
        if (!is_array($function) && method_exists($this, $function)) {
            $this->{$function}($params);
        } else if (is_array($function)) {
            foreach ($function as $func) {
                if (method_exists($this, $function))
                    $this->{$func}($params[$func]);
            }
        }
        return $this;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }
    public function getEntity()
    {
        return $this->returnType ?: false;
    }

    public function bring($alias)
    {
        $first = $this->first();
        if(!$first) return;
        $this->relationsAllowed = $first->getRelations();

        if (!is_array($alias)) {
            return $this->{$this->relationsAllowed[$alias]['type']}($alias);
        } else {
            foreach ($alias as $index => $option) {
                $query = "";
                $function = null;
                if (is_int($index)) {
                    $relation = $option;
                } else {
                    $relation = $index;
                    if (is_callable($option)) {
                        try {
                            $option($this);
                        } catch (\Exception $e) {
                            throw new \Exception('No se puede concatenar funciones del modelo relacional, en desarrollo ');
                            try {
                                $function = $option(new $this->relationsAllowed[$relation]['model']());
                            } catch (\Exception $e) {
                                throw new \Exception('Error en función no encontrada');
                            }
                        }
                    } else {
                        $query = $this->formatQuery($option);
                    }
                }
                $this->{$this->relationsAllowed[$relation]['type']}($relation, $query, $function);
            }
            return $this;
        }
    }
    /**
     * hasOne es cuando la tabla consultada se usa el _PK_ por lo tanto el atributo en la variable debe ser el foreing_key de la tabla local
     */
    public function hasOne($alias, $query = "", $function = null)
    {
        $relation = $this->relationsAllowed[$alias];

        $relation_table = new $relation['model']();

        $this->join($relation_table->getTableName() . ' as ' . $alias, $relation["foreign"] . "=" . $alias . '.' . $relation_table->getPrimaryKey() . $query);

        return $this;
    }


    /**
     * Añade como join una tabla de relación sin importar el tipo, la función devuelve segun pertenezca hasOne o hasMany
     * 
     * hasBelong se usa cuando queremos buscar el PK local en otra tabla, el atributo debe ser la columna FK de la tabla a buscar 
     */
    public function hasBelong($alias, $query = "", $function = null)
    {
        $relation = $this->relationsAllowed[$alias];

        $local_key = $relation['local_key'] ?? $this->getPrimaryKey();
        $relation_table = new $relation['model']();

        $this->join($relation_table->getTableName() . ' as ' . $alias, $alias . '.' . $relation["foreign"] . "=" . $local_key . $query);

        return $this;
    }

    /**
     * * TODO: El join es similar al hasOne, la diferenciaesta en que el resultado seran varias filas en vez de 1 como el hasOne
     */
    /**
     * Añade join una tabla de relación 1 a muchos
     * 
     * hasMany es cuando la tabla consultada se usa el _PK_ por lo tanto el atributo en la variable debe ser el foreing_key de la tabla local
     */
    public function hasMany($alias, $query = "", $function = null)
    {
        $relation = $this->relationsAllowed[$alias];
        if (class_exists($relation['model'])) {
            $relation_table = new $relation['model']();
            $this->join($relation_table->getTableName() . ' as ' . $alias, $relation["foreign"] . "=" . $alias . '.' . $relation_table->getPrimaryKey() . $query);
        } else {
            $db      = \Config\Database::connect();
            $builder = $db->table($relation['model']);
            //$this->join();
            //$this->attributes[$alias] = $builder->where($relation['foreign'], $this->attributes[$this->primaryKey])->get()->getResultArray();
        }
        return $this;
    }

    /**
     * TODO: hace falta implementarlo bien, requiere armar los 2 join, un hasOne y un hasBelong se pueden reutilizar, pero tener cuidado con los temporales
     */
    public function belongToMany($alias, $query = "", $function = null)
    {
        $relation = $this->relationsAllowed[$alias];
        if (class_exists($relation['pivot'])) {
            //$entityClass = new $relation['pivot']() ;
            //$pivotResult = $entityClass->where($relation['foreign'],$this->attributes[$this->primaryKey])->findAll();
            $this->relationKey["temp_pivot"] = [
                'model'         => $relation['pivot'],
                'foreign'       => $relation['foreign']
            ];
            $pivotResult = $this->hasBelong("temp_pivot");
        } else {
            $db      = \Config\Database::connect();
            $builder = $db->table($relation['pivot']);
            $pivotResult = $builder->where($relation['foreign'], $this->attributes[$this->primaryKey])->get()->getResultArray();
        }

        $results = [];

        foreach ($pivotResult as $pivot) {
            $foreing_pivot = $pivot[$relation['related_key']];
            $this->relationKey["temp_pivot"] = [
                'model'         => $relation['model'],
                'foreign'       => $foreing_pivot
            ];
            $associative_result = $this->hasOne("temp_pivot");
            array_push($results, $this->attributes["temp_pivot"]);
        }
        unset($this->attributes["temp_pivot"]);
        $this->attributes[$alias] = $results;

        return $this;
    }

    private function formatQuery($query)
    {
        $connector = ['AND', 'OR', 'and', 'or', 'Or', 'And'];

        $query = explode(" ", trim($query));

        if (in_array($query[0], $connector)) {
            $query = " " . join(" ", $query);
        } else {
            $query = " AND " . join(" ", $query);
        }

        return $query;
    }

    public function orderDesc($field){
        $this->orderBy($field, 'DESC');
    }
}
