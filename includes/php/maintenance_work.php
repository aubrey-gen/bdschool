<?php
    /*  session_start();    */    
    /*  Connect to database */
    include('./includes/php/DB_CONNECT.php');
    /*  Collect data */
    $my_table = $link->query("SELECT ipkManagerId, manager_password, sSalt, sPepper FROM sys_managers
    LEFT JOIN spice ON sys_managers.ipkManagerId = spice.ifkManagerId", PDO::FETCH_NAMED);
     
    $log_pass = FALSE;
    
    if($my_table != FALSE){
        include('./includes/php/functions.php');
        foreach($my_table as $row) {
            /*  check for clear text password   */
            if($row['sSalt'] == NULL || $row['sPepper'] == NULL){  
                $guidSalt = getGUID();
                $guidPepper = getGUID();
                $sOldPassword = $row['manager_password'];
                $iManager_Id = $row['ipkManagerId'];
                /*  generate secure password  from clear test password  */
                $sNewPassword = encPass($sOldPassword, $guidSalt, $guidPepper);            
                /*  update database with new secure password    */
                $sth = $link->prepare('UPDATE sys_managers SET manager_password = :psw WHERE ipkManagerId = :man_id ');
                $sth->bindParam(':man_id', $iManager_Id, PDO::PARAM_INT);
                $sth->bindParam(':psw', $sNewPassword, PDO::PARAM_STR);
                $sth->execute();
                /**/
                $sth = $link->prepare('INSERT INTO spice ( ifkManagerId, sSalt, sPepper) VALUES ( :man_id, :salt, :pepper ) ');
                $sth->bindParam(':man_id', $iManager_Id, PDO::PARAM_INT);
                $sth->bindParam(':salt', $guidSalt, PDO::PARAM_STR);
                $sth->bindParam(':pepper', $guidPepper, PDO::PARAM_STR);
                $sth->execute();
                $sth->closeCursor();
            }
         }
         echo '<p>Clear text password encrpted</p>';
    }
    else{
        echo '<p>Could not encrpyt clear text password</p>';
    }
    unset($link);
?>