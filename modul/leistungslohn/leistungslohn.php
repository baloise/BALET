<?php include("../session/session.php"); ?>
<?php include("../../database/connect.php"); ?>
<?php if($session_usergroup == 1) : ?>

    <head>
        <link rel="stylesheet" href="modul/leistungslohn/leistungslohn.css"/>
    </head>

    <h1 class="mt-5"><?php echo $translate["Leistungslohn"];?></h1>
    
    <?php
    
        $groups = "";
    
        //GRUPPEN
    
        $sql = "SELECT grou.ID AS groupID, grou.name AS groupName FROM `tb_group` AS grou WHERE grou.ID IN (3,4,5);";
        $groupResult = $mysqli->query($sql);
        
        if (isset($groupResult) && $groupResult->num_rows > 0) {
            while($row = $groupResult->fetch_assoc()) {
                
                $groupID = $row['groupID'];
                $groupName = $row['groupName'];
                
                $users = "";
                
                //LEHRLINGE
                
                $sql = "SELECT us.ID AS userID, us.firstname AS userFirstname, us.lastname AS userLastname, us.bKey AS userBkey FROM `tb_user` AS us WHERE us.tb_group_ID = ". $groupID ." AND deleted IS NULL";
                $result = $mysqli->query($sql);
                
                if (isset($result) && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        
                        $userID = $row['userID'];
                        $userFirstname = $row['userFirstname'];
                        $userLastname = $row['userLastname'];
                        $userBkey = $row['userBkey'];
                        
                        if($groupID == 3){
                            
                            $cycleList = '
                                <!-- BERECHNUNGSZYKLEN INFORMATIKER -->
                                                    
                                <div class="row">
                                    <div class="col-lg-12 card">
                                        <div class="row cycleHeader" userID="'.$userID.'" cycleID="1" onclick="toggleCycle('.$userID.', 1);">
                                            <div class="col-10">
                                                <h2>'.$translate["Lohnzyklus"].' ' . $translate["Jahr"] .' 3</h2>
                                            </div>
                                            <div class="col-2 text-right">
                                                <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="row cycleContent" userID="'.$userID.'" cycleID="1">
                                            
                                            <div class="col-12 text-center loading">
                                                <img src="img/loading2_big.gif"/>
                                            </div>
                                            
                                            <!-- AJAX CALL -->
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12 card">
                                        <div class="row cycleHeader" userID="'.$userID.'" cycleID="2" onclick="toggleCycle('.$userID.', 2);">
                                            <div class="col-10">
                                                <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 7</h2>
                                            </div>
                                            <div class="col-2 text-right">
                                                <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="row cycleContent" userID="'.$userID.'" cycleID="2">
                                            
                                            <div class="col-12 text-center loading">
                                                <img src="img/loading2_big.gif"/>
                                            </div>
                                            
                                            <!-- AJAX CALL -->
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12 card">
                                        <div class="row cycleHeader" userID="'.$userID.'" cycleID="3" onclick="toggleCycle('.$userID.', 3);">
                                            <div class="col-10">
                                                <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 8</h2>
                                            </div>
                                            <div class="col-2 text-right">
                                                <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="row cycleContent" userID="'.$userID.'" cycleID="3">
                                            
                                            <div class="col-12 text-center loading">
                                                <img src="img/loading2_big.gif"/>
                                            </div>
                                            
                                            <!-- AJAX CALL -->
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- BERECHNUNGSZYKLEN INFORMATIKER ENDE -->
                            ';
                            
                        } else if ($groupID == 4 || $groupID == 5){
                            
                            $cycleList = '
                                <!-- BERECHNUNGSZYKLEN KV VERSICHERUNG -->
                                                    
                                <div class="row">
                                    <div class="col-lg-12 card">
                                        <div class="row cycleHeader" userID="'.$userID.'" cycleID="4" onclick="toggleCycle('.$userID.', 4);">
                                            <div class="col-10">
                                                <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 5</h2>
                                            </div>
                                            <div class="col-2 text-right">
                                                <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="row cycleContent" userID="'.$userID.'" cycleID="4">
                                            
                                            <div class="col-12 text-center loading">
                                                <img src="img/loading2_big.gif"/>
                                            </div>
                                            
                                            <!-- AJAX CALL -->
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-lg-12 card">
                                        <div class="row cycleHeader" userID="'.$userID.'" cycleID="5" onclick="toggleCycle('.$userID.', 5);">
                                            <div class="col-10">
                                                <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 6</h2>
                                            </div>
                                            <div class="col-2 text-right">
                                                <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="row cycleContent" userID="'.$userID.'" cycleID="5">
                                            
                                            <div class="col-12 text-center loading">
                                                <img src="img/loading2_big.gif"/>
                                            </div>
                                            
                                            <!-- AJAX CALL -->
                                            
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- BERECHNUNGSZYKLEN KV VERSICHERUNG ENDE -->
                            ';
                            
                        } 
                        
                        $usersEntry = '
                            <div class="row">
                                <div class="col-lg-12 card" style="background-color: #F1F4FB;">
                                    <div class="row userHeader" userID="'.$userID.'" onclick="toggleUser('.$userID.');">
                                        <div class="col-10">
                                            <h2>'.$userFirstname.' '.$userLastname.' ('.$userBkey.')</h2>
                                        </div>
                                        <div class="col-2 text-right">
                                            <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    <div class="row userContent" userID="'.$userID.'">
                                        <div class="col-12">
                                            <hr/>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                
                                                    <!-- BERECHNUNGSZYKLEN -->
                                                    
                                                    '.$cycleList.'
                                                    
                                                    <!-- BERECHNUNGSZYKLEN ENDE -->
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';
                        
                        $users = $users . $usersEntry;
                        
                    }
                 } else {
                    $users = "Keine Benutzer gefunden.";
                 }
                
                //LEHRLINGE ENDE
                
                $groupsEntry = '
                    <div class="row">
                        <div class="col-lg-12 card">
                            <div class="row groupHeader" groupID="'.$groupID.'" onclick="toggleGroup('.$groupID.');">
                                <div class="col-10">
                                    <h2>'.$translate[$groupName].'</h2>
                                </div>
                                <div class="col-2 text-right">
                                    <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="row groupContent" groupID="'.$groupID.'">
                                <div class="col-12">
                                    <hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            '.$users.'
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
                
                $groups = $groups . $groupsEntry;
                
            }
            
            echo $groups;
            
        } else {
            echo $translate['Keine Gruppen gefunden'].".";
        }
        
        //GRUPPEN ENDE
    ?>

    
    <br/>
    <hr/>
    <h2><?php echo $translate["CSV-Export"];?></h2>
    <script type="text/javascript" src="modul/leistungslohn/leistungslohn.js"></script> 

<?php elseif($session_usergroup == 4 || $session_usergroup == 3 || $session_usergroup == 5) : ?>
    
    <head>
        <link rel="stylesheet" href="modul/leistungslohn/leistungslohn.css"/>
    </head>

    <h1 class="mt-5"><?php echo $translate["Leistungslohn"];?></h1>
    
    <?php
    
    //LEHRLINGE
                
        $sql = "SELECT us.ID AS userID, us.firstname AS userFirstname, us.lastname AS userLastname, us.bKey AS userBkey FROM `tb_user` AS us WHERE us.ID = $session_userid";
        $result = $mysqli->query($sql);
        
        if (isset($result) && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $userID = $row['userID'];
                $userFirstname = $row['userFirstname'];
                $userLastname = $row['userLastname'];
                $userBkey = $row['userBkey'];
                
                if($session_usergroup == 3){
                    
                    $cycleList = '
                        <!-- BERECHNUNGSZYKLEN INFORMATIKER -->
                                            
                        <div class="row">
                            <div class="col-lg-12 card">
                                <div class="row cycleHeader" userID="'.$userID.'" cycleID="1" onclick="toggleCycle('.$userID.', 1);">
                                    <div class="col-10">
                                        <h2>'.$translate["Lohnzyklus"].' '.$translate["Jahr"].' 3</h2>
                                    </div>
                                    <div class="col-2 text-right">
                                        <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="row cycleContent" userID="'.$userID.'" cycleID="1">
                                    
                                    <div class="col-12 text-center loading">
                                        <img src="img/loading2_big.gif"/>
                                    </div>
                                    
                                    <!-- AJAX CALL -->
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 card">
                                <div class="row cycleHeader" userID="'.$userID.'" cycleID="2" onclick="toggleCycle('.$userID.', 2);">
                                    <div class="col-10">
                                        <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 7</h2>
                                    </div>
                                    <div class="col-2 text-right">
                                        <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="row cycleContent" userID="'.$userID.'" cycleID="2">
                                    
                                    <div class="col-12 text-center loading">
                                        <img src="img/loading2_big.gif"/>
                                    </div>
                                    
                                    <!-- AJAX CALL -->
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 card">
                                <div class="row cycleHeader" userID="'.$userID.'" cycleID="3" onclick="toggleCycle('.$userID.', 3);">
                                    <div class="col-10">
                                        <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 8</h2>
                                    </div>
                                    <div class="col-2 text-right">
                                        <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="row cycleContent" userID="'.$userID.'" cycleID="3">
                                    
                                    <div class="col-12 text-center loading">
                                        <img src="img/loading2_big.gif"/>
                                    </div>
                                    
                                    <!-- AJAX CALL -->
                                    
                                </div>
                            </div>
                        </div>
                        
                        <!-- BERECHNUNGSZYKLEN INFORMATIKER ENDE -->
                    ';
                    
                } else if ($session_usergroup == 4 || $session_usergroup == 5){
                    
                    $cycleList = '
                        <!-- BERECHNUNGSZYKLEN KV VERSICHERUNG -->
                                            
                        <div class="row">
                            <div class="col-lg-12 card">
                                <div class="row cycleHeader" userID="'.$userID.'" cycleID="4" onclick="toggleCycle('.$userID.', 4);">
                                    <div class="col-10">
                                        <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 5</h2>
                                    </div>
                                    <div class="col-2 text-right">
                                        <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="row cycleContent" userID="'.$userID.'" cycleID="4">
                                    
                                    <div class="col-12 text-center loading">
                                        <img src="img/loading2_big.gif"/>
                                    </div>
                                    
                                    <!-- AJAX CALL -->
                                    
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 card">
                                <div class="row cycleHeader" userID="'.$userID.'" cycleID="5" onclick="toggleCycle('.$userID.', 5);">
                                    <div class="col-10">
                                        <h2>'.$translate["Lohnzyklus"].' '.$translate["Semester"].' 6</h2>
                                    </div>
                                    <div class="col-2 text-right">
                                        <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="row cycleContent" userID="'.$userID.'" cycleID="5">
                                    
                                    <div class="col-12 text-center loading">
                                        <img src="img/loading2_big.gif"/>
                                    </div>
                                    
                                    <!-- AJAX CALL -->
                                    
                                </div>
                            </div>
                        </div>
                        
                        <!-- BERECHNUNGSZYKLEN KV VERSICHERUNG ENDE -->
                    ';
                    
                } 
                
                $usersEntry = '
                    <div class="row">
                        <div class="col-lg-12 card" style="background-color: #F1F4FB;">
                            <div class="row userHeader" userID="'.$userID.'" onclick="toggleUser('.$userID.');">
                                <div class="col-10">
                                    <h2>'.$userFirstname.' '.$userLastname.' ('.$userBkey.')</h2>
                                </div>
                                <div class="col-2 text-right">
                                    <i class="fa fa-chevron-down" style="margin-top: 5px;" aria-hidden="true"></i>
                                </div>
                            </div>
                            <div class="row userContent" userID="'.$userID.'">
                                <div class="col-12">
                                    <hr/>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                        
                                            <!-- BERECHNUNGSZYKLEN -->
                                            
                                            '.$cycleList.'
                                            
                                            <!-- BERECHNUNGSZYKLEN ENDE -->
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                ';
                
                echo $usersEntry;
                
            }
        } else {
            echo $translate["Kein Benutzer gefunden"].".";
        }
        
        //LEHRLINGE ENDE
    
    ?>
    
    <script type="text/javascript" src="modul/leistungslohn/leistungslohn.js"></script> 
    
<?php else : ?>
    
    <br/><br/>
    
    <div class='alert alert-danger'>
        <strong><?php echo $translate["Fehler"];?> </strong> <?php echo $translate["Ihr Account wurde keiner Gruppe zugewiesen, oder Ihnen fehlen Rechte."];?>
    </div>
    
<?php endif; ?>