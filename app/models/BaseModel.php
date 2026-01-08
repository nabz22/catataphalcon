<?php

namespace App\Models;

/**
 * Simple Model base class (without Phalcon)
 */
abstract class BaseModel {
    protected static $db;
    protected static $table;
    protected $attributes = [];
    protected $messages = [];
    
    public static function setDb($db) {
        self::$db = $db;
    }
    
    public function __get($name) {
        return $this->attributes[$name] ?? null;
    }
    
    public function __set($name, $value) {
        $this->attributes[$name] = $value;
    }
    
    public static function find($options = []) {
        $table = static::$table;
        $order = $options['order'] ?? 'id DESC';
        $sql = "SELECT * FROM $table ORDER BY $order";
        
        $result = self::$db->getConnection()->query($sql);
        $objects = [];
        
        while ($row = $result->fetch_assoc()) {
            $obj = new static();
            $obj->attributes = $row;
            $objects[] = $obj;
        }
        
        return $objects;
    }
    
    public static function findFirstById($id) {
        $table = static::$table;
        $id = (int)$id;
        $sql = "SELECT * FROM $table WHERE id = $id LIMIT 1";
        
        $result = self::$db->getConnection()->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            $obj = new static();
            $obj->attributes = $row;
            return $obj;
        }
        
        return null;
    }
    
    public function save() {
        if ($this->validation() === false) {
            return false;
        }
        
        if (isset($this->attributes['id']) && !empty($this->attributes['id'])) {
            return $this->update();
        } else {
            return $this->create();
        }
    }
    
    private function create() {
        $table = static::$table;
        $this->attributes['created_at'] = date('Y-m-d H:i:s');
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        
        $columns = implode(',', array_keys($this->attributes));
        $escapedValues = array_map(function($v) { 
            return self::$db->getConnection()->real_escape_string($v);
        }, array_values($this->attributes));
        $values = "'" . implode("','", $escapedValues) . "'";
        
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        
        return self::$db->getConnection()->query($sql);
    }
    
    private function update() {
        $table = static::$table;
        $id = $this->attributes['id'];
        $this->attributes['updated_at'] = date('Y-m-d H:i:s');
        
        $sets = [];
        foreach ($this->attributes as $key => $value) {
            if ($key !== 'id') {
                $escaped = self::$db->getConnection()->real_escape_string($value);
                $sets[] = "$key = '$escaped'";
            }
        }
        
        $setStr = implode(',', $sets);
        $sql = "UPDATE $table SET $setStr WHERE id = $id";
        
        return self::$db->getConnection()->query($sql);
    }
    
    public function delete() {
        $table = static::$table;
        $id = $this->attributes['id'] ?? null;
        
        if (!$id) {
            return false;
        }
        
        $sql = "DELETE FROM $table WHERE id = $id";
        return self::$db->getConnection()->query($sql);
    }
    
    public function validation() {
        return true;
    }
    
    public function getMessages() {
        return $this->messages;
    }
    
    protected function addMessage($field, $message) {
        $this->messages[] = (object)['field' => $field, 'message' => $message];
    }
}
