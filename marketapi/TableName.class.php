<?php

abstract class TableName {
    public $tablePDOMySQL,$tbName;
    function __construct($pdo){
        $this->tablePDOMySQL = PDOMySQL::getObject($pdo);
        $this->tbName = strtolower(get_class($this));
    }

    function insert($data){
        $result = $this->tablePDOMySQL->insert($this->tbName,$data);
        return $result;
    }

    function delete($condition = ""){
        $result = $this->tablePDOMySQL->delete($this->tbName,$condition);
        return $result;
    }

    function truncate(){
        $result = $this->tablePDOMySQL->truncate($this->tbName);
        return $result;
    }

    function update($data,$condition = array()){
        $result = $this->tablePDOMySQL->update($this->tbName,$data,$condition);
        return $result;
    }

    function select_fetchAll($config = array()){
        return $this->tablePDOMySQL->select_fetch_all($this->tbName, $config);
    }

    function select_fetchOne($config = array()){
        return $this->tablePDOMySQL->select_fetch_one($this->tbName, $config);
    }

    function select_rowNum($config = array()){
        return $this->tablePDOMySQL->select_num($this->tbName, $config);
    }
}
