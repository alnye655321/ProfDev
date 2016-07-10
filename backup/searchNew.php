<?php
include 'connect.php';

//get search term
    $searchTerm = $_GET['term'];


    //get matched data from skills table
    $query = $con->query("SELECT DISTINCT LastName FROM TeachingInfo WHERE LastName LIKE '%".$searchTerm."%' ORDER BY LastName");
   
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['LastName'];
    }
    
    //return json data
    echo json_encode($data);



//Sample first,last name search
/*    
    $searchTerm = $_GET['term'];


    //get matched data from skills table
    $query = $con->query("SELECT SNum, Type FROM Activity WHERE SNum LIKE '%".$searchTerm."%' ORDER BY SNum");
   
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['SNum'].",".$row['Type'];
    }
    
    //return json data
    echo json_encode($data);
*/
?>