<?php

class Database
{
    protected
        $table = '',
        $host = '',
        $user = '',
        $pass = '',
        $dbname = '',
        $conn = null;
    public function __construct($config){
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->dbname = $config['dbname'];
        $this->connect();
    }
    protected function connect()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch (PDOException $e){
            echo 'Lỗi kết nối: ' . $e->getMessage();
        }
    }
    public function get($data = [])
    {
        try{
            $stmt = join("=? and ",array_keys($data));
            $stmt .= '=?';
            $sqlString= "select * from $this->table where $stmt";
            $query = $this->conn->prepare($sqlString);
            $query->execute(array_values($data));
            $result = null;
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $result = $row;
            }
            return $result;
        }catch (PDOException $e){
            echo "Lỗi đọc dữ liệu: " . $e->getMessage();
        }
    }
    public function gets($data = [],$start = 0,$limit = 0)
    {
        try{
            $result = [];
            $stmt = join("=? and ",array_keys($data));
            $sqlString = "select * from $this->table";
            if(count($data) > 0){
                $stmt.= '=?';
                $sqlString = $sqlString . " where $stmt";
            }
            if($limit > 0){
                $sqlString = $sqlString . " limit $start, $limit";
                echo $sqlString;
            }
            $query = $this->conn->prepare($sqlString);
            $query->execute(array_values($data));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
            return $result;
        }catch (PDOException $e){
            echo "Lỗi đọc dữ liệu: " . $e->getMessage();
        }
    }
    public function insert($data = [])
    {
        try{
            $stmt1 = join(',',array_keys($data));
            $stmt2 = join(',',array_fill(0,count($data),'?'));
            $sqlString = "insert into $this->table($stmt1) values ($stmt2)";
            $query = $this->conn->prepare($sqlString);
            $query->execute(array_values($data));

        }catch (PDOException $e){
            echo 'Lỗi thêm dữ liệu: ' . $e->getMessage();
        }
    }
    public function update($id,$data = []){
        try{
            $stmt = join('=?,',array_keys($data));
            $stmt .= "=?";
            $sqlString = "update $this->table set $stmt where id = $id";
            $query = $this->conn->prepare($sqlString);
            $query->execute(array_values($data));
        }catch (PDOException $e){
            echo 'Lỗi thêm dữ liệu: ' . $e->getMessage();
        }
    }
    public function delete($id){
        try {
            $stmt = "delete from $this->table where id = $id";
            $query = $this->conn->prepare($stmt);
            $query->execute();
        }
        catch (PDOException $e){
            echo 'Lỗi thêm dữ liệu: ' . $e->getMessage();
        }
    }
    public function search($name = 1, $col = ['*'],$option = [],$start = 0,$limit = 0){
        try{
            $stmt1 = join(',',$col);
            $stmt2 = $name == 1 ? $name : "name like N'%$name%'";
            $sqlString = "select $stmt1 from $this->table where $stmt2";
            $sqlString .= " and " . array_keys($option)[0] . " =?";
            if($limit > 0){
                $sqlString = $sqlString . " limit $start, $limit";
                echo $sqlString;
            }
            $query = $this->conn->prepare($sqlString);
            $query->execute(array_values($option));
            $resultArr = [];
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $resultArr[] = $row;
            }
            return $resultArr;
        }catch (PDOException $e){
            echo "Lỗi đọc dữ liệu: " . $e->getMessage();
        }
    }
    public function table($tableName)
    {
        $this->table = $tableName;
        return $this;
    }
}
?>
