<?php
class PDOMySQL {
        private $pdo;
        public $fetchStyle = PDO::FETCH_ASSOC ;
        static private $pdomysql = null;
        private function __construct($pdo){
            $this->pdo = $pdo;
        }

        function insert($tbName,$data){
            $k_arr = array_keys($data);
            $columnsString = implode(',',$k_arr);
            $valuesString = implode("','",$data);
            $insertStatement = "insert $tbName($columnsString) values ('$valuesString')";
          //echo $insertStatement;
            $stmt = $this->pdo->prepare($insertStatement);
            $result = $stmt->execute();
            return $result;
        }

        function delete($tbName, $condition = "")
        {
            $where = ($condition == "") ? "" : ('where ' . $condition);
            $deleteStatement = "delete from $tbName $where";
            //echo $deleteStatement;
            $stmt = $this->pdo->prepare($deleteStatement);
            $result = $stmt->execute();
            return $result;
        }

        function update($tbName, $data, $condition = '')
        {
            foreach ($data as $key => $value) {
                $unit[] = "$key = '$value'";
            }
            $sets = implode(',', $unit);
            $where = ($condition == "") ? "" : ('where ' . $condition);
            $updateStatement = "update $tbName set $sets  $where";
            //echo $updateStatement;
            $stmt = $this->pdo->prepare($updateStatement);
            $result = $stmt->execute();
            return $result;
        }

        function build_select_statement($tbName, $config = array()){
            if (!isset($config['columns'])){
                $config['columns'] = '*';
            }
            $selectStatement = "select " . $config['columns'] . " from $tbName";
            if (isset($config['where']) && $config['where'] != '') {
                $selectStatement .= " where " . $config['where'];
            }
            if (isset($config['group']) && $config['group'] != '') {
                $selectStatement .= " group by " . $config['group'];
            }
            if (isset($config['order']) && $config['order'] != '') {
                $selectStatement .= " order by " . $config['order'];
            }
            if (isset($config['having']) && $config['having'] != '') {
                $selectStatement .= " having " . $config['order'];
            }
            if (isset($config['limit']) && $config['limit'] != '') {
                $selectStatement = $selectStatement . " limit " . $config['limit'];
            }
            return $selectStatement;
        }

        function select_fetch_all($tbName, $config = array()){
            $selectStatement = $this->build_select_statement($tbName, $config );
           //echo $selectStatement;
            $stmt = $this->pdo->prepare($selectStatement);
            $stmt->execute();
            return $stmt->fetchAll($this->fetchStyle);
        }

        function  select_fetch_one($tbName, $config = array()){
            $selectStatement = $this->build_select_statement($tbName, $config );
            //echo $selectStatement;
            $stmt = $this->pdo->prepare($selectStatement);
            $stmt->execute();
            return $stmt->fetch($this->fetchStyle);
        }

        function  select_num($tbName, $config = array()){
            $selectStatement = $this->build_select_statement($tbName, $config );
            //echo $selectStatement;
            $stmt = $this->pdo->prepare($selectStatement);
            $stmt->execute();
            $select_num = $stmt->rowCount();
            return $select_num;
        }
        //该方法实现对取出结果集数组的格式自定义
        function set_fetch_style($mode = NULL){
            if($mode != PDO::FETCH_ASSOC && $mode != PDO::FETCH_NUM && $mode != PDO::FETCH_BOTH){
                $mode = PDO::FETCH_ASSOC;
            }
            $this->fetchStyle = $mode ;
        }

        function truncate($tbName){
        $truncateStatement = "TRUNCATE $tbName ";
        $stmt = $this->pdo->prepare($truncateStatement);
        $result = $stmt->execute();
        return $result;
        }

        static function getObject($pdo){
            if(is_null(self::$pdomysql)){
                self::$pdomysql = new PDOMySQL($pdo);
            }
            return self::$pdomysql;
        }

        private function __clone(){

        }
}
