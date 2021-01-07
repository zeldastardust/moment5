<?php
require 'config/db.php';
require 'objects/Courses.php';

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER['REQUEST_METHOD'];
//if id is in url
if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare course object
$courses = new Courses($db);

switch ($method) {
    case 'GET':
        
        // query edu
        $stmt = $courses->read();
        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {

            // edu array
            $course_arr = array();
            $course_arr["records"] = array();

            // retrieve our table contents
            // fetch() is faster than fetchAll()
            // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);

                $courses_item = array(
                    "id" => $id,
                    "code" => $code,
                    "name" => $name,
                    "progression"=>$progression,
                    "syllabus" => $syllabus
                );

                array_push($course_arr["records"], $courses_item);
            }

            // set response code - 200 OK
            http_response_code(200);

            // show products data in json format
            echo json_encode($course_arr);
        }else{
             // set response code - 404 Not found
             http_response_code(404);

             // tell the user product does not exist
             echo json_encode(array("message" => "courses does not exist."));
        }

        // set ID property of record to read
        $courses->id = isset($_GET['id']) ? $_GET['id'] : die();

        // read the details of product to be edited
        $courses->readOne($id);

        if ($courses->code != null) {
            // create array
            $course_arr = array(
                "id" =>  $courses->id,
                "code" => $courses->code,
                "name" => $courses->name,
                "progression" => $courses->progression,
                "syllabus" => $courses->syllabus
            );

            // set response code - 200 OK
            http_response_code(200);

            // make it json format
            echo json_encode($course_arr);
        } else {
            // set response code - 404 Not found
            http_response_code(404);

            // tell the user courses does not exist
            echo json_encode(array("message" => "courses does not exist."));
        }

        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));

        // make sure data is not empty
        if (
            !empty($data->code) &&
            !empty($data->name) &&
            !empty($data->progression)&&
            !empty($data->syllabus) 
        ) {

            // set education property values
            $courses->code = $data->code;
            $courses->name = $data->name;
            $courses->progression = $data->progression;
            $courses->syllabus = $data->syllabus;

            // create the edu
            if ($courses->create()) {
                // set response code - 201 created
                http_response_code(201);
                // tell the user
                echo json_encode(array("message" => "course was created."));
            }
            // if unable to create the edu, tell the user
            else {
                // set response code - 503 service unavailable
                http_response_code(503);
                // tell the user
                echo json_encode(array("message" => "Unable to create course."));
            }
        }
        // tell the user data is incomplete
        else {
            // set response code - 400 bad request
            http_response_code(400);
            // tell the user
            echo json_encode(array("message" => "Unable to create course. Data is incomplete."));
        }
        break;
    case 'PUT':
        // get id of edu to be edited
$data = json_decode(file_get_contents("php://input")); 
// set ID property of edu to be edited
//$education->id = $data->id; 
$courses->id = $id;
// set edu property values
$courses->code = $data->code;
$courses->name = $data->name;
$courses->progression=$data->progression;
$courses->syllabus = $data->syllabus;

// update the edu
if($courses->update()){ 
    // set response code - 200 ok
    http_response_code(200); 
    // tell the user
    echo json_encode(array("message" => "course was updated."));
} 
// if unable to update the edu, tell the user
else{ 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update course"));
}
        break;
    case "DELETE":
        // get edu id
$data = json_decode(file_get_contents("php://input"));
// set edu id to be deleted
 
$courses->id = $id;
// delete the edu
if($courses->delete()){ 
    // set response code - 200 ok
    http_response_code(200); 
    // tell the user
    echo json_encode(array("message" => "course was deleted."));
}
 
// if unable to delete the edu
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete course."));
}
        break;
}
//return result as JSON
//echo json_encode($result);

//close db
$db = $database->close();
