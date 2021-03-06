<?php

    include("../../includes/session.php");
    include("../../database/connect.php");
    include('../../includes/testInput.php');

    if($session_usergroup != 1){
        die("Permission denied.");
    }

    $uploaddir = 'upload/';
    $uploadfile = $uploaddir . test_input(basename($_FILES['userfile']['name']));
    $group = test_input($_POST['group']);
    $lang = test_input($_POST['lang']);

    if(strtolower(pathinfo($uploadfile,PATHINFO_EXTENSION)) != "pdf"){
        echo "The File you uploaded doesn't end on .pdf<br/>";
        echo "Failed to transmit Data. <a onclick='window.history.back();'>Go Back</a>";
        die();
    }

    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

        $pathToFile = "modul/reglement/" .$uploadfile;

        $sql = "UPDATE `tb_text` SET $lang = ? WHERE `type` = 'reglement' AND `tb_group_ID` = ?;";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('si', $pathToFile, $group);
        $stmt->execute();

        echo('Changes successfully done<script type="text/javascript">history.go(-1);</script>');

    } else {

        echo "ERROR: Failed to transmit Data.";

    }

?>
