<?php  
    include 'config/database.php';
    $tuid = $_POST['tuid'];

    $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,               "https://api.hub2.io/v1/transactions/$tuid/status/in");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
        curl_setopt($ch, CURLOPT_HEADER,            false);
        curl_setopt($ch, CURLOPT_GET,              false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    true);
        curl_setopt($ch, CURLOPT_HTTPHEADER            
        );

        $return           = curl_exec($ch);
        $result          = json_decode($return, true);
        
        $statut          = $result['status']['status']; 
        $codeH           = $result['status']['code']; 
        $tuid            = $result['tuid']; 
        $codetransaction = $result['purchase_ref']; 
        $action          = $result['action']; 
        $success         = $result['success']; 
        curl_close($ch);
        
        if(isset($statut) AND $statut != 'successful'){
            //CONNEXION A LA BASE DE DONNEES
            $statuts = 5;
           
            try {
                $dsn = "mysql:dbname=$dbname;host=$servername";
                $ps = new PDO($dsn, $dbuser, $dbpwd, array(PDO::ATTR_PERSISTENT => true));
                
            }
            catch (PDOException $e)
            {
                print 'Erreur ! : '.$e->getMessage().'<br/>';
                echo 'N° : '.$e->getCode();
                //echo 'N° : '.$e->getLine();
                die();
            }

            //Modification des donn�es
            $update = $ps->prepare('UPDATE transactions SET  `status` = :nv_status WHERE transaction_id = :us_code');
            $update->execute(array(
                'nv_status' =>  ($statuts),
                'us_code' =>  ($codetransaction)
            )) or die(print_r($update->errorInfo()));

        }
?>



