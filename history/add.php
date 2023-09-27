<?php
include '../connection.php';

$id_user    = $_POST['id_user'];
$id_masterpengeluaran = "6";
$type       = $_POST['type'];
$date       = $_POST['date'];
$total      = $_POST['total'];
$details    = $_POST['details'];
$created_at = $_POST['created_at'];
$updated_at = $_POST['updated_at'];

$sql_check = "SELECT * FROM history WHERE id_user = '$id_user' AND date='$date' AND type='$type'";
$result_check = $connect->query($sql_check);

$isAvail = false;
while($row = mysqli_fetch_assoc($result_check)){
    $json = json_decode($row['details'], true);
    if($details == $json){
        $isAvail = true;
        break;
    }
}
//$result_check->num_rows > 0
if ($isAvail) {
    echo json_encode(array(
        "success"=>false,
        "message"=>"date",
    ));
}else{
    /* $sql =  "INSERT INTO history
            SET
            id_user = '$id_user',
            id_masterpengeluaran = '$id_masterpengeluaran',
            type = '$type',
         
            date = '$date',
            total = '$total',
            details = '$details',
            created_at = '$created_at',
            updated_at = '$updated_at' "; */
    if(strtolower($type) == "pengeluaran"){
        $sqlCheckSaldo = "SELECT 
                        id_user, 
                        `type`, 
                        SUM(total) AS total
                    FROM 
                        `history` 
                    WHERE 
                        id_user = '$id_user'
                        AND MONTH(`date`) = MONTH(CURRENT_DATE) 
                        AND YEAR(`date`) = YEAR(CURRENT_DATE) 
                    GROUP BY 
                        id_user, `type`";

        $checkSaldo = $connect->query($sqlCheckSaldo);
        $income = 0;
        $outCome = 0;

        if ($checkSaldo->num_rows > 0) {    
            while ($row = $checkSaldo->fetch_assoc()) {
                $type = $row["type"]; // Pemasukan / Pengeluaran        
                if ($type=="Pemasukan"){
                    $income = floatval($row['total']);
                }else {
                    $outCome = floatval($row['total']);
                }
            }
        }

        $resultSaldo = (float) $income - (float) $outCome;
        if($total > $resultSaldo){
            echo json_encode(array(
                "success"=>false,
                "message"=>"Jumlah Pengeluaran Lebih Besar dari pada Saldo",
                "isSaldo" => true,
            ));
            return;
        }
    }

    $sql = "INSERT INTO history VALUES (NULL, '$id_user', '$id_masterpengeluaran', '$type', '$date', '$total', '$details', NOW(), '$updated_at')";

    $result = $connect->query($sql);

    if ($result) {
        echo json_encode(array(
            "success"=>true,
        ));
    }else{
        echo json_encode(array(
            "success"=>false,
            "message"=>"Gagal",
        ));
    }
}