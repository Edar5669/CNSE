<?php
        include '../config/database.php';


        //CONNEXION A LA BASE DE DONNEES
        $timestamp= date('Y-m-d H:m:s', time());
        $created_at = $timestamp;
        $updated_at =$timestamp;

        $userid = addslashes($_POST['usersid']);
        $transaction_id = addslashes($_POST['transaction_id']);
        $amount = addslashes($_POST['amount']);
        $tuid = addslashes($_POST['tuid']);
        $query = "INSERT INTO transactions (user_id, transaction_id, amount, tuid, created_at, updated_at) 
            VALUES('$userid', '$transaction_id', '$amount', '$tuid', '$created_at', '$updated_at')";
        $res = mysqli_query($db,$query);

        print(mysqli_error($db));
        if ($res) {
            return true;
        } else {
            return false;
            
        }
    mysqli_close($db);
    

?>
