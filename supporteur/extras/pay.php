<?php
        $hidden_infos = array(
            "merchant_id" => "577",
            "api_key"       => "8b3bf9dd83ab6d672dc85972e0d39055288dac98baa1793826e7f046a8556b7abaddcd49c9759cdd591106d002d607ab9ed9289fedfb57b568255267cfd33e1d",
            "in"    => array(
                "method"    => "mb",
            ),			
        );

        // On merge les paramètres des informations de la page JS/AJAX (POSTdata)
        $merged_in          = array_merge($_POST['in'], $hidden_infos['in']);
        $hidden_infos['in'] = $merged_in;

        $adv_post = array_merge($_POST, $hidden_infos);

        // On encode le JSON
        $data_string = json_encode($adv_post);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,               "https://api.hub2.io/v1/transactions/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);
        curl_setopt($ch, CURLOPT_HEADER,            false);
        curl_setopt($ch, CURLOPT_POST,              false);
        curl_setopt($ch, CURLOPT_POSTFIELDS,        $data_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $return = curl_exec($ch);

        $error_msg = curl_error($ch);
        if ( $error_msg )
            echo $error_msg;
        else {
            // On retourne à la requête ajax le tuid et le token
            echo $return;
        }


        curl_close($ch);

    ?>
