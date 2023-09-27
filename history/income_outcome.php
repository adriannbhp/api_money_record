<?php
include "../connection.php";

$id_user = $_POST['id_user'];
$id_pengeluaran = $_POST['id'];
$type = $_POST['type'];

$sql = "SELECT id_history, date, total, type FROM history
        WHERE
        id_user='$id_user' AND type='$type'
        ORDER BY date DESC
        ";
$result = $connect->query($sql);

//$sql1 = "SELECT * FROM tb_masterpengeluaran WHERE id = '$id_pengeluaran' " ;

//$result1 = $connect->query($sql1);

//$data1 = array();
   // while ($row1 = $result1->fetch_assoc()) {
     //   $data1[] = $row1;
    //}

//$hasil = $data1[0];    

if ($result->num_rows > 0) {  
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(array(   
        "success" => true,
        "data" => $hasil
    ));
}else{
    echo json_encode(array(   
        "success" => false        
    ));

    
}

