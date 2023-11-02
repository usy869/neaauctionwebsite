<?php 
    header("Content-Type: application/json; charset=UTF-8");  
    
    require '../connect/connectUserBookshop.php';
        
    //set default response string
    $response = array(
      "code"    => 0,
      "message" => "failed to run SQL");
    
    //instantiate the prepared parameterised SQL statement
    $stmt = $conn->prepare("
        CREATE TABLE bookSold(
        saleId   INT,
        ISBN     CHAR(10),
        quantity INT,
        PRIMARY KEY (saleId, ISBN),
        FOREIGN KEY(saleId) REFERENCES sale(saleId),
        FOREIGN KEY(ISBN)   REFERENCES book(ISBN)
        )Engine=InnoDB;"
    );
        
    //execute the Query
    try {
        $stmt->execute();

        //set the response as success
        $response["code"]    = 1;
        $response["message"] = "success";
    }       
    catch (PDOException $e){
        //get the error message
        $response["message"] .= "\n" . $e->getMessage() . "\n";
    }   

    //output response
    echo json_encode($response);

    //close the connection  
    $conn = null ;
?>