<?php

header("content-type: application/json; charset=UTF-8");

function main(){
    /* MAIN ROUTINE
    $dataReceived object collection of sent data
    $response object collection of data sent back
    */

    //include PHP script for accessing database
    require "../connect/connectUser.php";
    require "../token/token.php";

    //capture input and decode it
    
    $dataReceived = json_decode(file_get_contents("php://input"));

    //debugging
    //echo ("The data received is: \n");
    //print_r($dataReceived);
    //die();

    $token = $dataReceived->webToken;
    $secret= getKey();

    $response = getAllAuctions($conn);

    if (verifyToken($token,$secret)){

        $response = getAllAuctions($conn);

            //send the results back
        echo json_encode($response);

    }

    //close the connection
    $conn = null;
    
}

function getAllAuctions($conn){
    /*
    FUNCTION to run a SELECT query to check credentials
    $conn object connection to database
    $data JSON collection of data from client
    $response dictionary JSON to return to client
    $query object
    $sql string holds SQL script
    */
    //set default response as a dictionary
    $response = array(
        "successCode" => 0,
        "data"        => "",
        "message"     => ""
    );

    //prepare the parameterised SQL for the query
    $sql = "SELECT *
    FROM auction
    ORDER BY auctionID ASC";

    //set up the query object
    $query = $conn->prepare($sql);

        try{
        //execute the query
        $query->execute();

        //set the fetch mode to get keys and values from dictionary
        $query->setFetchMode(PDO::FETCH_ASSOC);

        //get all results as a variable
        $allAuctions = $query->fetchAll();
        //debugging
        //print_r($allBooks);
        //die();

        //set the response JSON
        $response["successCode"] = 1;
        $response["data"] = $allAuctions;
        $response["message"] = "success";
            
        }

        catch (PDOException $e){
            //deal with error in running the query
            $response["message"] = $e->getMessage();
        }
    
    //return the response
    return $response;

    
}

main();

?>


