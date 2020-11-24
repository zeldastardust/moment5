<?php
class Courses{
 
    // database connection and table name
    private $conn;
    private $table_name = "courses";
 
    // course properties
    public $id;
    public $code;
    public $name;
    public $progression;
    public $syllabus;
    
    
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    // read products
function read(){
 
    // select all query
    $query = "SELECT
                id, code, name, progression, syllabus
            FROM
                 . $this->table_name" ;
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}
// create edu
function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                code=:code, name=:name, progression=:progression, syllabus=:syllabus";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->place=htmlspecialchars(strip_tags($this->code));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->category=htmlspecialchars(strip_tags($this->progression));
    $this->startedu=htmlspecialchars(strip_tags($this->syllabus));
    
 
    // bind values
    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":name", $this->name);
    $stmt->bindParam(":progression", $this->progression);
    $stmt->bindParam(":syllabus", $this->syllabus);
   
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}
// used when filling up the update product form
function readOne($id){
 
    // query to read single record
    $query = "SELECT
                id, code, name,progression, syllabus 
            FROM
                " . $this->table_name . " c
            WHERE
                id = $id
                LIMIT 1";
            
 
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
 
    // bind id of education to be updated
    $stmt->bindParam(1, $this->id);
 
    // execute query
    $stmt->execute();
 
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
    // set values to object properties
    $this->code = $row['code'];
    $this->name = $row['name'];
    $this->progression = $row['progression'];
    $this->syllabus = $row['syllabus'];

}
// update the edu

function update(){
 
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                code = :code,
                name = :name,
                progression=:progression,
                syllabus = :syllabus
                
            WHERE
                id = :id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->code=htmlspecialchars(strip_tags($this->code));
    $this->name=htmlspecialchars(strip_tags($this->name));
    $this->progression=htmlspecialchars(strip_tags($this->progression));
    $this->syllabus=htmlspecialchars(strip_tags($this->syllabus));
   
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind new values
    $stmt->bindParam('code', $this->code);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':progression', $this->progression);
    $stmt->bindParam(':syllabus', $this->syllabus);
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
// delete the edu
function delete(){
 
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
 
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}
}
?>
