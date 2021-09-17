<?php

namespace App\Entities;

use CodeIgniter\Entity;

/**
 * Esta clase solo crea funciones para facilitar el manejo de relaciones entre entidades,
 * fue creada pensada solo para la plataforma IEF, por lo cual no hace manejo de excepciones
 * ni usa llaves relacionales por defecto, debido a la estandarización del proyecto,
 * se debe establecer todas las variables desde la entidad que se desee utilizar(Ver ejemplo mas abajo).
 */

class CoreEntity extends Entity
{

    /**
     * EJEMPLO: Así se debe armar la variable de relaciones dentro de cada entidad, no colocarla dentro de esta clase
     *  protected $relationKey = [
     *      "{Alias}" => [          ->Nombre con el cual se podrá llamar las funciones y como se llamará el objeto de retorno
     *          'model'         => "",      ->Ruta completa del modelo a usarse (Posiblemente cambiar a solo el nombre del modelo)
     *          'type'          => "",      ->Establece que tipo de relación se contruye, basado en la estructura de BD (hasOne,hasBelong,hasMany,belongToMany)
     *          'foreign'       => "",      ->Nombre de la columna que indica la relación FK en la relación directa
     *          ****SOLO belongToMany****
     *          'pivot'         => ""       ->Tabla intermedia entre la relación de belongToMany
     *          'parent_key'    => ""       ->ForeingKey en tabla pivot de la entidad de origen
     *          'related_key'   => ""       ->ForeingKey en tabla pivot de la entidad relacionada
     *          ****OPCIONAL****
     *          'local_key'     => ""       ->si no es declarada se usará el pk por defecto
     *];
     */

    private $clusterRelation = [];
    public function getData($alias)
    {
        $this->setLocalKey();
        if (!is_array($alias)) {
            if (isset($this->relationKey[$alias]['type'])) {
                $this->{$this->relationKey[$alias]['type']}($alias);
                $this->clusterRelation[$alias] = [];
                return $this;
            } else {
                return false;
            }
        } else {
            foreach ($alias as $index => $option) {
                list($relation, $invokes) = $this->getRelationAlias($index, $option);
                

                if (isset($this->relationKey[$relation]['type']) && $this->relationKey[$relation]['type'] != null) {
                    $this->{$this->relationKey[$relation]['type']}($relation, $invokes);
                    $this->clusterRelation[$relation] = [];
                } else {
                    $error = false;

                    if (empty($this->clusterRelation)) {
                        return false;
                        //throw new \Exception(' No existe relación (' . $relation . ') en (' . get_class($this) . '), recordar colocar primero la relación mas cercana O' . $e->xdebug_message);
                    }

                    foreach ($this->clusterRelation as $sub_relation => $option) {
                        if ($this->attributes[$sub_relation] instanceof CoreEntity) {
                            try {
                                $this->attributes[$sub_relation]->getData([$relation =>  $alias[$relation] ?? ""]);
                                $this->clusterRelation[$sub_relation][$relation] = [];
                                $error = false;
                                // break 1;
                            } catch (\Exception $i) {
                                $error = $i->getMessage() . ", regreso a " . get_class($this);
                            }
                        }

                        if (is_array($this->attributes[$sub_relation])) {
                            foreach ($this->attributes[$sub_relation] as $entity_relation) {
                                if ($entity_relation instanceof CoreEntity) {
                                    try {
                                        $this->useInvokes($entity_relation, isset($alias[$relation]) ? $alias[$relation] : "");
                                        $entity_relation->getData([$relation => isset($alias[$relation]) ? $alias[$relation] : ""]);
                                        $this->clusterRelation[$sub_relation][$relation] = [];
                                        $error = false;
                                    } catch (\Exception $i) {
                                        $error = $i->getMessage() . ", regreso a " . get_class($this);
                                        break 1;
                                    }
                                }
                            }
                        }
                    }

                    if ($error) {
                        throw new \Exception("Error en rastreo:" . $error);
                    }
                }
            }
            return $this;
        }
    }
    public function getExceptionsData($alias)
    {
        $this->setLocalKey();
        if (!is_array($alias)) {
            try {
                $this->{$this->relationKey[$alias]['type']}($alias);
                $this->clusterRelation[$alias] = [];
                return $this;
            } catch (\Exception $e) {
                throw new \Exception(' No existe relación (' . $alias . ') en (' . get_class($this) . ')');
            }
        } else {
            foreach ($alias as $index => $option) {
                list($relation, $invokes) = $this->getRelationAlias($index, $option);

                try {
                    $this->{$this->relationKey[$relation]['type']}($relation, $invokes);
                    $this->clusterRelation[$relation] = [];
                } catch (\Exception $e) {

                    $error = false;

                    if (empty($this->clusterRelation)) {
                        throw new \Exception(' No existe relación (' . $relation . ') en (' . get_class($this) . '), recordar colocar primero la relación mas cercana O' . $e->xdebug_message);
                    }


                    foreach ($this->clusterRelation as $sub_relation => $option) {
                        if ($this->attributes[$sub_relation] instanceof CoreEntity) {
                            try {
                                $this->attributes[$sub_relation]->getData([$relation => isset($alias[$relation]) ? $alias[$relation] : ""]);
                                $this->clusterRelation[$sub_relation][$relation] = [];
                                $error = false;
                                break 1;
                            } catch (\Exception $i) {
                                $error = $i->getMessage() . ", regreso a " . get_class($this);
                            }
                        }

                        if (is_array($this->attributes[$sub_relation])) {
                            foreach ($this->attributes[$sub_relation] as $entity_relation) {
                                if ($entity_relation instanceof CoreEntity) {
                                    try {
                                        $this->useInvokes($entity_relation, isset($alias[$relation]) ? $alias[$relation] : "");
                                        $entity_relation->getData([$relation => isset($alias[$relation]) ? $alias[$relation] : ""]);
                                        $this->clusterRelation[$sub_relation][$relation] = [];
                                        $error = false;
                                    } catch (\Exception $i) {
                                        $error = $i->getMessage() . ", regreso a " . get_class($this);
                                        break 1;
                                    }
                                }
                            }
                        }
                    }

                    if ($error) {
                        throw new \Exception("Error en rastreo:" . $error);
                    }
                }
            }
            return $this;
        }
    }

    public function getRelations()
    {
        return $this->relationKey;
    }

    /**
     * Añade como join una tabla de relación 1 a 1 devolviendo una entidad para mejor manejo de los datos.
     * 
     * hasOne es cuando la tabla consultada tiene el _FK_ por lo tanto el atributo en la variable debe ser el foreing_key
     */
    public function hasOne($alias, $invokes = "")
    {
        $entityClass = new $this->relationKey[$alias]['model']();

        $this->useInvokes($entityClass, $invokes);

        if (is_numeric($this->relationKey[$alias]['foreign'])) {
            $this->attributes[$alias] = $entityClass->find($this->relationKey[$alias]['foreign']);
        } else {
            $this->attributes[$alias] = $entityClass->find($this->attributes[$this->relationKey[$alias]['foreign']]);
        }


        return $this;
    }


    /**
     * Añade como join una tabla de relación sin importar el tipo, la función devuelve segun pertenezca hasOne o hasMany
     * 
     * hasBelong se usa cuando queremos buscar el PK local en otra tabla, el atributo debe ser la columna FK de la tabla a buscar 
     */
    public function hasBelong($alias, $invokes = "")
    {
        $entityClass = new $this->relationKey[$alias]['model']();
        $join = $entityClass->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']]);
        
        if (isset($this->relationKey[$alias]['invoke'])) {
            $this->useInvokes($entityClass, $this->relationKey[$alias]['invoke']);
        }
        $this->useInvokes($entityClass, $invokes);
        $count = $join->countAllResults();
        /**
         * Valida si es solo 1 resultado crea 1 sola entidad, si son varios creará varias entidades 
         * lo cual afecta el manejo en el controlador.
         * Se organiza de esta manera para las relaciones que son 1-1 y no tener enredos en el controlador
         */
        if (isset($this->relationKey[$alias]['invoke'])) {
            $this->useInvokes($entityClass, $this->relationKey[$alias]['invoke']);
        }
        $this->useInvokes($entityClass, $invokes);


        if ($count == 1) {
            $this->attributes[$alias] = $entityClass->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']])->first();
        } else if ($count !== 0) {
            $this->attributes[$alias] = $entityClass->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']])->findAll();
        } else {
            $this->attributes[$alias] = false;
        }
        return $this;
    }
    public function hasFirst($alias, $invokes = "")
    {
        $entityClass = new $this->relationKey[$alias]['model']();
        $join = $entityClass->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']]);
        
        if (isset($this->relationKey[$alias]['invoke'])) {
            $this->useInvokes($entityClass, $this->relationKey[$alias]['invoke']);
        }
        $this->useInvokes($entityClass, $invokes);
        $count = $join->countAllResults();
        /**
         * Valida si es solo 1 resultado crea 1 sola entidad, si son varios creará varias entidades 
         * lo cual afecta el manejo en el controlador.
         * Se organiza de esta manera para las relaciones que son 1-1 y no tener enredos en el controlador
         */
        if (isset($this->relationKey[$alias]['invoke'])) {
            $this->useInvokes($entityClass, $this->relationKey[$alias]['invoke']);
        }
        $this->useInvokes($entityClass, $invokes);


        if ($count > 0) {
            $this->attributes[$alias] = $entityClass->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']])->first();
        } else {
            $this->attributes[$alias] = false;
        }
        return $this;
    }

    /**
     * Añade como join una tabla de relación 1 a muchos devolviendo un array de entidades
     * 
     * hasOne es cuando la tabla consultada tiene el _FK_ por lo tanto el atributo en la variable debe ser el foreing_key
     */
    public function hasMany($alias, $invokes = "")
    {
        if (class_exists($this->relationKey[$alias]['model'])) {
            $entityClass = new $this->relationKey[$alias]['model']();

            $this->useInvokes($entityClass, $invokes);

            if (isset($this->relationKey[$alias]['invoke'])) {
                $this->useInvokes($entityClass, $this->relationKey[$alias]['invoke']);
            }

            $this->attributes[$alias] = $entityClass->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']])->findAll();
            if (isset($this->relationKey[$alias]['order_key'])) {
                $this->attributes[$alias] = $this->setKeysByPrimaryKey($this->relationKey[$alias]['order_key'], $this->attributes[$alias]);
            }
        } else {
            $db      = \Config\Database::connect();
            $builder = $db->table($this->relationKey[$alias]['model']);
            $this->attributes[$alias] = $builder->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']])->get()->getResultArray();
        }
        return $this;
    }
    private function setKeysByPrimaryKey($primaryKey, $arrayData)
    {
        $response = array();
        foreach ($arrayData as $data) {
            $response[$data->{$primaryKey}] = $data;
        }
        return $response;
    }

    /**
     * TODO: ojo los invokes como deberian funcionar?
     */
    public function belongToMany($alias, $invokes = "")
    {
        if (class_exists($this->relationKey[$alias]['pivot'])) {
            $entityClass = new $this->relationKey[$alias]['pivot']();
            // $this->useInvokes($entityClass, $invokes);  // Aca en realidad ni hace consultas....
            if (isset($this->relationKey[$alias]['invoke'])) {
                $this->useInvokes($entityClass, $this->relationKey[$alias]['invoke']);
                if(is_array($invokes)){
                    $invokes[] = $this->relationKey[$alias]['invoke'];
                }elseif($invokes!=""){
                    //TODO: en mi logica esto debería ser asi pero en $invokes esta llegando un objecto entity y no entiendo porque
                    // $invokes = array($invokes,$this->relationKey[$alias]['invoke']);
                    $invokes = $this->relationKey[$alias]['invoke'];
                }else{
                    $invokes = $this->relationKey[$alias]['invoke'];
                }
            }

            $this->relationKey["temp_pivot"] = [
                'model'         => $this->relationKey[$alias]['pivot'],
                'foreign'       => $this->relationKey[$alias]['foreign'],
                'local_key'       => $this->relationKey[$alias]['local_key']
            ];
            $this->hasBelong("temp_pivot", $invokes);
            $pivotResult = $this->attributes['temp_pivot'];
        } else {
            $db      = \Config\Database::connect();
            $builder = $db->table($this->relationKey[$alias]['pivot']);
            $pivotResult = $builder->where($this->relationKey[$alias]['foreign'], $this->attributes[$this->relationKey[$alias]['local_key']])->get()->getResultArray();
        }
        if ($pivotResult !== false && !is_array($pivotResult) /*&& !is_numeric(array_key_first($pivotResult))*/)
            $pivotResult = array(0 => $pivotResult);
        $results = [];
        //if (is_array($pivotResult)) {
        if ($pivotResult !== false) {
            foreach ($pivotResult as $pivot) {
                $foreing_pivot = is_object($pivot) ? $pivot->{$this->relationKey[$alias]['related_key']} : $pivot[$this->relationKey[$alias]['related_key']];
                $this->relationKey["temp_pivot"] = [
                    'model'         => $this->relationKey[$alias]['model'],
                    'foreign'       => $foreing_pivot
                ];
                $this->hasOne("temp_pivot", $invokes);
                if ($this->attributes["temp_pivot"] != null)
                    array_push($results, $this->attributes["temp_pivot"]);
            }
        }
        /* } else {
            $foreing_pivot = $pivotResult[$this->relationKey[$alias]['related_key']];
            $this->relationKey["temp_pivot"] = [
                'model'         => $this->relationKey[$alias]['model'],
                'foreign'       => $foreing_pivot
            ];
            $this->hasOne("temp_pivot", $invokes);
            if ($this->attributes["temp_pivot"] != null)
                array_push($results, $this->attributes["temp_pivot"]);
        }*/


        unset($this->attributes["temp_pivot"]);
        $this->attributes[$alias] = count($results) == 1 ? array_shift($results) : $results;

        return $this;
    }


    private function getRelationAlias($index, $value)
    {
        if (is_int($index)) {
            $relation = $value;
            $invokes = "";
        } else {
            $relation = $index;
            $invokes = $value;
        }
        return array($relation, $invokes);
    }

    private function setLocalKey()
    {

        foreach ($this->relationKey as $alias => $relation) {
            if (!isset($relation['local_key']) || $relation['local_key'] === "") {
                $this->relationKey[$alias]['local_key'] = $this->primaryKey;
            }
        }
    }

    private function useInvokes($entityClass, $invokes)
    {
        if ($invokes != "") {
            if (is_callable($invokes)) {
                $invokes($entityClass);
            } else if (!is_array($invokes) && method_exists($entityClass, $invokes)) {
                $entityClass->invoke($invokes);
            }
            if (is_array($invokes)) {
                foreach ($invokes as $invoke) {
                    if (method_exists($entityClass, $invoke))
                        $entityClass->invoke($invoke);
                }
            }
        }
    }
}
