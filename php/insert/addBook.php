<?php 
    header("content-type: application/json; charset=UTF-8");  

    function main(){
        /* MAIN ROUTINE
        @var $dataReceived  object  collection of data sent
        @var $response      object  collection of data to send back
        */


        //include PHP scripts for accessing the database
        require '../connect/connectUser.php';

        //capture the input and decode it
        $dataReceived = json_decode(file_get_contents('php://input'));
        
        //debugging
        //echo("The data received is: \n");
        //print_r($dataReceived);
        //die();

        //run the add user subroutine
        $response = addNewBook($conn, $dataReceived);
         
        //send the results back
        echo json_encode($response);

        //close the connection  
        $conn = null;
    }

    function addNewBook($conn, $data){
  /*
   FUNCTION to run a SELECT query to check credentials
   @params  $conn     object     connection to the database
   @params  $data     JSON       collection of data from client
   @var     $response dictionary JSON to return to client
   @var     $query    object     query object
   @var     $sql      string     holds the SQL script
  */
   //set default response as an associative array (dictionary)  
  $response = array(
        "successCode" => 0,
        "data"        => "",
        "message"     => ""
  );

  //prepare the parameterised SQL for the query
  $sql = "INSERT INTO books (ISBN, title, author, publisher, publication, cover) 
          VALUES(:ISBN, :title, :author, :publisher, :publication, :cover)";
  
  //set up the query object
  $query = $conn->prepare($sql);

  //bind data to the parameters  
  $query->bindParam(':ISBN', $data->ISBN);                 
  $query->bindParam(':title',     $data->title);  
  $query->bindParam(':author', $data->author);
  $query->bindParam(':publisher', $data->publisher);
  $query->bindParam(':publication', $data->publication);
  $query->bindParam(':cover', $data->cover);

  try {
     //execute the Query     
     $query->execute();
     //amend the response object
     $response["successCode"] = 1;
     $response["message"] = "success - book added";

  } catch (PDOException $e) {
     //deal with error in running the query
    $response["message"] = $e->getMessage();
  }

   //return the response
  return $response;
}
    
    //run main
    main();
?>
