<?php
include '../connect.php';

//get search term
    $searchTerm = $_GET['term'];


    //get matched data from skills table
    $query = $con3->query("SELECT DISTINCT LastName, FirstName FROM Activity WHERE LastName LIKE '%".$searchTerm."%' ORDER BY LastName");
   
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['LastName'] . ", " . $row['FirstName'];
    }
    
    //return json data
    echo json_encode($data);



//Sample first,last name search
/*    
    $searchTerm = $_GET['term'];


    //get matched data from skills table
    $query = $con3->query("SELECT SNum, Type FROM Activity WHERE SNum LIKE '%".$searchTerm."%' ORDER BY SNum");
   
    while ($row = $query->fetch_assoc()) {
        $data[] = $row['SNum'].",".$row['Type'];
    }
    
    //return json data
    echo json_encode($data);
*/
?>