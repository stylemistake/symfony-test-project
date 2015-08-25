<?php

namespace AppBundle\Entity;

use Lazer\Classes\Database as Lazer;
use Lazer\Classes\Helpers\Validate as LazerValidate;
use Lazer\Classes\LazerException;
use Lazer\Classes\Relation as LazerRelation;

abstract class AbstractLazerEntity {

    public $_serialize = [];

    public $id;

    public function __construct($data = null) {
        // Initialize table
        try {
            LazerValidate::table($this->_table)->exists();
        } catch (LazerException $e) {
            // Table doesn't exist
            Lazer::create($this->_table, $this->_schema);
        }
        if (is_object($data)) {
            // Convert stdObject to Array
            $data = get_object_vars($data);
        }
        if (is_array($data)) {
            // Initialize this object with provided data
            foreach ($data as $i => $value) {
                $this->{$i} = $value;
            }
        }
    }

    public static function table() {
        $class = get_called_class();
        $instance = new $class();
        return Lazer::table($instance->_table);
    }

    public static function instantiate($items) {
        $class = get_called_class();
        $result = [];
        foreach ($items as $item) {
            $instance = new $class($item);
            // TODO: load method is very expensive on large data sets
            $result[] = $instance->load();
        }
        return $result;
    }

    public static function getAll() {
        $items = self::table()->findAll();
        return self::instantiate($items);
    }

    public static function getAllOrdered($column = null, $direction = null) {
        if (empty($column) || empty($direction)) {
            return self::getAll();
        }
        $items = self::table()->orderBy($column, strtoupper($direction))->findAll();
        return self::instantiate($items);
    }

    public function save() {
        $subject = Lazer::table($this->_table);
        // Try to find existing record before saving
        if (isset($this->id)) {
            $subject = $subject->find($this->id);
        }
        foreach ($this->_schema as $id => $type) {
            if (!isset($this->{$id})) {
                continue;
            }
            if (in_array($id, $this->_serialize)) {
                $subject->{$id} = serialize($this->{$id});
                continue;
            }
            if ($type === 'boolean') {
                $subject->{$id} = !!$this->{$id};
                continue;
            }
            if ($type === 'integer') {
                $subject->{$id} = intval($this->{$id});
                continue;
            }
            if ($type === 'float' || $type === 'double') {
                $subject->{$id} = floatval($this->{$id});
                continue;
            }
            if ($type === 'string') {
                $subject->{$id} = strval($this->{$id});
                continue;
            }
            // In case nothing works...
            throw new LazerException('Wrong data type');
        }
        $subject->save();
        return $this;
    }

    public function load() {
        $subject = Lazer::table($this->_table)->find($this->id);
        foreach ($this->_schema as $id => $type) {
            if (!isset($subject->{$id})) {
                continue;
            }
            if (in_array($id, $this->_serialize)) {
                // Serialize types which cannot be stored in Lazer
                $this->{$id} = unserialize($subject->{$id});
                continue;
            }
            $this->{$id} = $subject->{$id};
        }
        return $this;
    }

    public function delete() {
        try {
            $subject = Lazer::table($this->_table)
                ->find($this->id)
                ->delete();
            return true;
        } catch (LazerException $e) {
            return false;
        }
        return false;
    }

}
