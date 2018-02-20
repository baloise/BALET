<?php include("../session/session.php"); ?>
<?php include("../../database/connect.php"); ?>

<?php if($session_usergroup == 1) : //HR ?>

    <h1 class="mt-5"><?php echo $translate["Noten"];?></h1>
    <h3><?php echo $translate["Lernende"];?></h3>
    
    <?php
    
        
        $llEntries = "";
        
        
        $sql1 = "SELECT ID, bKey, firstname, lastname, deleted FROM `tb_user` WHERE tb_group_ID IN (3, 4, 5) AND deleted IS NULL;";
        $result1 = $mysqli->query($sql1);
        if ($result1->num_rows > 0) {
            while($row1 = $result1->fetch_assoc()) {
                
                $llid = $row1['ID'];
                $llbkey = $row1['bKey'];
                $llfirst = $row1['firstname'];
                $lllast = $row1['lastname'];
                $llsubjs = 0;
                $llallavg = 0;
                $oldllsubsem = "";

                $gradesunderEntries = "";
                $subEntries = "";
                
                $sql2 = "SELECT us.subjectName, us.ID, us.correctedGrade, sem.semester FROM `tb_user_subject` AS us 
                        INNER JOIN tb_semester AS sem ON us.tb_semester_ID = sem.ID
                        WHERE us.tb_user_ID = $llid ORDER BY sem.semester DESC, us.creationDate DESC";
                        
                        
                $result2 = $mysqli->query($sql2);
                if ($result2->num_rows > 0) {
                    while($row2 = $result2->fetch_assoc()) {
                        
                        $llsubjs = $llsubjs + 1;
                        $llsubname = $row2['subjectName'];
                        $llsubid = $row2['ID'];
                        $llsubsem = $row2['semester'];
                        $llsubcorrgrade = $row2['correctedGrade'];
                        
                        $sql3 = "SELECT ID, grade, weighting FROM `tb_subject_grade` WHERE tb_user_subject_ID = $llsubid";
                        $result3 = $mysqli->query($sql3);
                        if ($result3->num_rows > 0) {
                            
                            $countgrades = 0;
                            $grades = 0;
                            $weights = 0;
                            
                            while($row3 = $result3->fetch_assoc()) {
                                
                                $gradeid = $row3['ID'];
                                $grade = $row3['grade'];
                                $gradeweight = $row3['weighting'];
                                
                                $grades = $grades + ($grade*$gradeweight);
                                $weights = $weights + $gradeweight;
                                $countgrades = $countgrades + 1;
                                
                            } 
                            
                            $subgradeavg = floor(($grades / $weights) * 100) / 100;
                            if($llsubcorrgrade){
                                $llallavg = $llallavg + $llsubcorrgrade;
                            } else {
                                $llallavg = $llallavg + $subgradeavg;   
                            }
                            
                        } else {
                            $subgradeavg = $translate["Keine Noten gefunden"].".";
                            $countgrades = 0;
                        }
                        
                        $countgradesunder = 0;
                        
                        $sql4 = "SELECT grade, title, reasoning FROM `tb_subject_grade` WHERE tb_user_subject_ID = $llsubid AND grade <= 4";
                        $result4 = $mysqli->query($sql4);
                        if ($result4->num_rows > 0) {
                            while($row4 = $result4->fetch_assoc()) {
                                
                                $gradesunderEntry = '
                                        <div class="row gradeBelow">
                                            <div class="col-lg-12">
                                                <div class="card" style="padding-top: 10px;margin-bottom:10px;">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <b>'. $llsubname .'</b>
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <b>'.$translate["Titel"].':</b> '. $row4['title'] .'
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <b>'.$translate["Note"].':</b> '. $row4['grade'] .'
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <b>'.$translate["Begründung"].':</b> '. $row4['reasoning'] .'<br/><br/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                ';
                                
                                $countgradesunder = $countgradesunder + 1;
                                $gradesunderEntries = $gradesunderEntries . $gradesunderEntry;
                            }
                        } else {
                            $countgradesunder = 0;
                        }
                        
                        if($llsubcorrgrade){
                            $subgradeavg = "<s>" . $subgradeavg . "</s> <b style='color:red;'>" . $llsubcorrgrade . "</b>";
                        }
                        
                        if($llsubsem == $oldllsubsem){
                            $subEntry = '
                                <tr>
                                    <th scope="row">'. $llsubname .'</th>
                                    <td>'. $countgrades .'</td>
                                    <td>'. $countgradesunder .'</td>
                                    <td class="subAvg" subjid="'. $llsubid .'">'. $subgradeavg .'</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <input placeholder="'.$translate["Notenschnitt"].'" subjid="'. $llsubid .'" type="number" class="form-control corrSubAvgNum"/>
                                            </div>
                                            <div class="col-lg-2" style="padding-left: 0;">
                                                <button type="button" subjid="'. $llsubid .'" class="btn btn-secondary corrSubAvg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            ';
                        
                        } else {
                            $subEntry = '
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12 contentToggler" semID="'.$llsubsem.'" style="margin-bottom: 10px; cursor: pointer">
                                <hr/>
                                <div class="row">
                                    <div class="col-10">
                                        <h2>'.$translate["Semester"].' '.$llsubsem.'</h2>
                                    </div>
                                    <div class="col-2 text-right">
                                        <i class="fa fa-chevron-down toggleDetails" style="margin-top: 5px;" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card toggleContent" semID="'.$llsubsem.'" style="display:none;">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">'.$translate["Fach/Modul"].'</th>
                                            <th scope="col">'.$translate["Noten"].'</th>
                                            <th scope="col">'.$translate["ungenügende"].'</th>
                                            <th scope="col">'.$translate["Notenschnitt"].'</th>
                                            <th scope="col">'.$translate["Korrektur"].'</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">'. $llsubname .'</th>
                                            <td>'. $countgrades .'</td>
                                            <td>'. $countgradesunder .'</td>
                                            <td class="subAvg" subjid="'. $llsubid .'">'. $subgradeavg .'</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-lg-10">
                                                        <input placeholder="'.$translate["Notenschnitt"].'" subjid="'. $llsubid .'" type="number" class="form-control corrSubAvgNum"/>
                                                    </div>
                                                    <div class="col-lg-2" style="padding-left: 0;">
                                                        <button type="button" subjid="'. $llsubid .'" class="btn btn-secondary corrSubAvg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                            ';
                            $oldllsubsem = $llsubsem;
                        }
                        
                        
                        $subEntries = $subEntries . $subEntry;
                        
                    }
                    
                } else {
                    $subEntries = "<tr><td colspan='5'>".$translate["Keine Fächer gefunden"].".</td><tr/>";
                }
                
                if($llsubjs > 0){
                    $calcavg = number_format((float)($llallavg/$llsubjs), 2, '.', '');
                } else {
                    $calcavg = "Keine Daten";
                }
                
                if($gradesunderEntries){
                    $gradesunderEntries = "<hr/><h3>".$translate["Ungenügende Noten"]."</h3>" . $gradesunderEntries;
                }
                
                $llEntry = '
                <div class="row">
                    <div class="card col-lg-12 userContentBox">
                        <div class="row userGradesHead header" containerID="'. $llid .'">
                            <div class="col-lg-6"><b>'. $llfirst . ' ' . $lllast .' ('. $llbkey .')</b></div>
                            <div class="col-lg-2">'.$translate["Notenschnitt"].': '. $calcavg .'</div>
                            <div class="col-lg-4 text-right"><i class="fa fa-chevron-down toggleDetails" style="margin-top: 5px;" aria-hidden="true"></i></div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 detailedGrades" containerID="'. $llid .'">
                                <div class="row">
                                    <div class="col-12">
                                        <hr/>
                                    </div>
                                </div>
                                <div class="xxx">
                                    <table>
                                        <tbody>
                                            
                                            '. $subEntries .'
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        '. $gradesunderEntries .'
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <hr/>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                '.$translate["Fächer/Module"].': '. $llsubjs .'
                                            </div>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';
                
                $llEntries = $llEntries . $llEntry;
                
            }
            
        } else {
            $llEntries = $translate["Keine Lernende im System"].". <br/>";
        }
        
        echo $llEntries;
    
    ?>
    
    
    
    <script type="text/javascript" src="modul/noten/noten.js"></script>  
    
    
<?php elseif($session_usergroup == 3 || $session_usergroup == 4 || $session_usergroup == 5) : //LLKV&IT ?>

    <?php
        
        
        //---------------------------------- Bestehende Fächer generieren ---------------------------------------
        $sql = "SELECT us.*, ss.ID AS subSemId  FROM `tb_user_subject` AS us
            INNER JOIN tb_semester AS ss ON ss.ID = us.tb_semester_ID
            WHERE us.tb_user_ID = $session_userid
            ORDER BY ss.semester DESC, us.`creationDate` DESC";
            
        $result = $mysqli->query($sql);
        $subjects = "";
        $currentSem = "";
        $subSemId = "";
        
        if (isset($result) && $result->num_rows > 0) {
            
            while($row = $result->fetch_assoc()) {
                
                $sql3 = "SELECT * FROM `tb_semester` WHERE tb_group_ID = $session_usergroup";
                $result3 = $mysqli->query($sql3);
                $semesterList = "";
                
                if (isset($result3) && $result3->num_rows > 0) {
                    
                    while($row3 = $result3->fetch_assoc()) {
                        if($row3['ID'] == $row['tb_semester_ID']){
                            $subjectSemester = $translate["Semester"].": " . $row3['semester'];
                        }
                        $semesterList = $semesterList . "<option value='". $row3['ID'] ."'>". $row3['semester'] ."</option>";
                    }
                
                }
                
                $subjectId = $row['ID'];
                $subSemId = $row['subSemId'];
                $subType = $row['school'];
                
                if($subType == 0){
                    $subType = $translate["Informatik-Modul"];
                } else {
                    $subType = $translate["Schulfach"];
                }
                
                $grades = "";
                $average = "";
                
                $sql2 = "SELECT * FROM `tb_subject_grade` WHERE tb_user_subject_ID = $subjectId;";
                $result2 = $mysqli->query($sql2);
                
                if (isset($result2) && $result2->num_rows > 0) {
                    $i = 0;
                    $allGrades = 0;
                    $allWeight = 0;
                    
                    $grades = $grades . '
                        <div class="alert alert-danger" fSubject="'. $row['ID'] .'" id="error" style="display: none;" role="alert"></div>
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>'.$translate["Datum"].'</th>
                                    <th>'.$translate["Titel"].'</th>
                                    <th>'.$translate["Note"].'</th>
                                    <th>'.$translate["Gewichtung"].'</th>
                                    <th></th>
                                </tr>
                            </thead>    
                            <tbody>
                    ';
                    
                    while($row2 = $result2->fetch_assoc()) {
                        
                        $i = $i + 1;
                        $allGrades = $allGrades + ($row2['grade']*($row2['weighting']));
                        $allWeight = $allWeight + $row2['weighting'];
                        
                        $gradeEntry = '
                            <tr gradeId="'. $row2['ID'] .'" class="gradeEntry">
                                <td>' . date('d.m.Y', strtotime($row2['creationDate'])) . '</td>
                                <td>' . $row2['title'] . '</td>
                                <td>' . $row2['grade'] . '</td>
                                <td>' . $row2['weighting'] . ' %</td>
                                <td><span class="fa fa-trash-o delGrade" gradeId="'. $row2['ID'] .'" aria-hidden="true" style="cursor: pointer;"></span></td>
                            </tr>
                        ';
                        
                        $grades = $grades . $gradeEntry;     
                             
                    }
                    
                    $grades = $grades . '
                            <tr>
                                <td><button type="button" fSubject="'. $row['ID'] .'" class="btn addGrade" style="padding-bottom: 0px; padding-top: 0px; margin-top: 5px;"><span class="fa fa-plus" aria-hidden="true" style="cursor: pointer;"></span></button></td>
                                <td><input fSubject="'. $row['ID'] .'" class="form-control fgradeTitle" type="text" placeholder="'.$translate["Titel"].'"/></td>
                                <td><input fSubject="'. $row['ID'] .'" class="form-control fgradeNote" min="1" max="6" type="number" placeholder="'.$translate["Titel"].'"/></td>
                                <td><input fSubject="'. $row['ID'] .'" class="form-control fgradeWeight" min="1" type="number" placeholder="'.$translate["Gewichtung"].' ('.$translate["in %"].')"/></td>
                                <td></td>
                            </tr>
                            <tr class="badDay" fSubject="'. $row['ID'] .'" style="display:none">
                                <td colspan="5"><textarea fSubject="'. $row['ID'] .'" placeholder="'.$translate["Begründung für Note unter 4.0"].'" class="form-control fgradeReason"></textarea></td>
                            </tr>
                        </tbody>
                    </table>  
                    ';
                    
                    if (isset($allGrades)){
                        $average = '<h2>'.$translate["Notenschnitt"].': ' . floor(($allGrades / $allWeight) * 100) / 100 . '</h2>';
                    }
                    
                } else {
                    $grades = '
                    <p>'.$translate["Noch keine Noten vorhanden"].'.</p>
                    <div class="alert alert-danger" fSubject="'. $row['ID'] .'" id="error" style="display: none;" role="alert"></div>
                    <table>
                        <tbody>
                            <tr>
                                <td><button type="button" fSubject="'. $row['ID'] .'" class="btn addGrade" style="padding-bottom: 0px; padding-top: 0px; margin-top: 5px;"><span class="fa fa-plus" aria-hidden="true" style="cursor: pointer;"></span></button></td>
                                <td><input fSubject="'. $row['ID'] .'" class="form-control fgradeTitle" type="text" placeholder="'.$translate["Titel"].'"/></td>
                                <td><input fSubject="'. $row['ID'] .'" class="form-control fgradeNote" min="1" max="6" type="number" placeholder="'.$translate["Titel"].'"/></td>
                                <td><input fSubject="'. $row['ID'] .'" class="form-control fgradeWeight" min="1" type="number" placeholder="'.$translate["Gewichtung"].' ('.$translate["in %"].')"/></td>
                                <td></td>
                            </tr>
                            <tr class="badDay" fSubject="'. $row['ID'] .'" style="display:none">
                                <td colspan="5"><textarea fSubject="'. $row['ID'] .'" placeholder="'.$translate["Begründung für Note unter 4.0"].'" class="form-control fgradeReason"></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                    <br/>
                    ';
                }
                

                if($subSemId == $currentSem){
                    $sorterDiv = "";
                } else if($currentSem == ""){
                    $sorterDiv = "
                    <div class='col-lg-12'>
                        <div class='divtoggler' subSemid='".$subSemId."' style='cursor:pointer;'>
                            <hr/>
                            <div class='row'>
                                <div class='col-lg-10'>
                                    <h2> " . $subjectSemester . " </h2>
                                </div>
                                <div class='col-lg-2 text-right'>
                                    <i class='fa fa-chevron-down toggleDetails' style='margin-top: 5px;' aria-hidden='true'></i>
                                </div>
                            </div>
                        </div>
                        <div class='divtogglercontent' subSemid='".$subSemId."'>
                        
                    ";
                } else {
                    $sorterDiv = "
                    </div>
                    <div class='divtoggler' subSemid='".$subSemId."' style='cursor:pointer;'>
                        <hr/>
                        <div class='row'>
                            <div class='col-lg-10'>
                                <h2> " . $subjectSemester . " </h2>
                            </div>
                            <div class='col-lg-2 text-right'>
                                <i class='fa fa-chevron-down toggleDetails' style='margin-top: 5px;' aria-hidden='true'></i>
                            </div>
                        </div>
                    </div>
                    <div class='divtogglercontent' style='display:none;' subSemid='".$subSemId."'>
                    ";
                }
                
                $subjectEntry = '
                    '. $sorterDiv .'
                        <div fSubject="'. $row['ID'] .'" class="card col-lg-10 delSubTag" style="padding: 20px;margin-top: 5px; margin-left:auto; margin-right:auto;">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h2>'. $row['subjectName'] .'</h2>
                                </div>
                                <div class="col-lg-6" style="text-align: right;">
                                    '. $average .'
                                </div>
                            </div>
                            <br/>
                            
                            <div class="row">
                                <div class="col-lg-11" style="margin-left: auto; margin-right: auto;">
                                '. $grades .'
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <a href="#" class="deleteSubject" subjectId="'. $row['ID'] .'">
                                        <span class="fa fa-trash-o delSubject" subjectId="'. $row['ID'] .'" aria-hidden="true" style="cursor: pointer; font-size: larger;"></span> '.$translate["Fach löschen"].'
                                    </a>
                                </div>
                                <div class="col-lg-6" style="text-align: right;">
                                    '. $subjectSemester .' ('.$subType.')
                                </div>
                            </div>
                        </div>
                ';
                     
                     
                $currentSem = $subSemId;
                     
                $subjects = $subjects . $subjectEntry;
                            
            }
        } else {
            $subjects = "<p>".$translate["Noch keine Fächer vorhanden"].".</p>";
            
            $sql3 = "SELECT * FROM `tb_semester` WHERE tb_group_ID = $session_usergroup";
            $result3 = $mysqli->query($sql3);
            $semesterList = "";
                
            if (isset($result3) && $result3->num_rows > 0) {
                    
                while($row3 = $result3->fetch_assoc()) {
                    $semesterList = $semesterList . "<option value='". $row3['ID'] ."'>". $row3['semester'] ."</option>";
                }
            }
        }
        
        //---------------------------------- Bestehende Fächer generieren ende ---------------------------------------
        
        
        
    ?>

    <h1 class="mt-5"><?php echo $translate["Noten"];?></h1>
    <p></p>
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="alert alert-success col-lg-10" id="addedNotif" style="display: none; margin-bottom: 0px;">
            <strong></strong> <?php echo $translate["Eintrag wurde hinzugefügt"];?>.
        </div>
    </div>
    <div class="row">
            <?php echo $subjects; ?>
    </div>
        
        <!-- Neues Fach hinzufügen -->
    
            <hr/>
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="alert alert-success col-lg-10" id="addedNotif2" style="display: none; margin-bottom: 0px;">
                        <strong></strong> <?php echo $translate["Fach wurde hinzugefügt"];?>.
                    </div>
                </div>
            </div>
            <div class="col-lg-10 card" style="padding: 20px;margin: 5px; margin-left:auto; margin-right:auto;">
                <div class="row">
                    <?php if($session_usergroup == 3){echo '<div class="col-lg-4">';} else {echo '<div class="col-lg-6">';} ?>
                        <input type="text" id="newSubNam" class="form-control" placeholder="Fach">
                    </div>
                
                    <?php
                    
                        if($session_usergroup == 3){
                            echo '
                            <div class="col-lg-4" id="LIT">
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" selected="" value="1" class="btn btnSelect">'.$translate["Schulfach"].'</button>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" selected="" value="0" class="btn btnSelect">'.$translate["IT-Modul"].'</button>
                                    </div>
                                </div>
                            </div>';
                        }
                    
                    ?>
                    
                    <?php if($session_usergroup == 3){echo '<div class="col-lg-4">';} else {echo '<div class="col-lg-6">';} ?>
                        <select class="form-control" id="newSubSem" placeholder="<?php echo $translate["Zählt in Semester"];?>">
                            <option><?php echo $translate["Semester"];?>:</option>
                            <?php echo $semesterList; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12" style="margin-top: 10px;">
                        <button type="button" class="btn col-lg-12" id="addSubject">
                            <span class="fa fa-plus" aria-hidden="true" style="cursor: pointer;"></span><b> <?php echo $translate["Neues Fach hinzufügen"];?></b>
                        </button>
                        <br/><br/>
                        <div class="alert alert-danger" id="errorForm" style="display: none;" role="alert"></div>
                    </div>
                </div>
            </div>
    </div>
    
    <script type="text/javascript" src="modul/noten/noten.js"></script>  
    
<?php else : ?>
    
    <br/><br/>
    
    <div class='alert alert-danger'>
        <strong><?php echo $translate["Fehler"];?> </strong> <?php echo $translate["Ihr Account wurde keiner Gruppe zugewiesen, oder Ihnen fehlen Rechte"];?>.
    </div>
    
<?php endif; ?>