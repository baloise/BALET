<?php include("../../includes/session.php"); ?>
<?php include("../../database/connect.php"); ?>
<?php include("../../includes/alerts.php"); ?>

<?php if($session_usergroup == 1) : ?>

    <h1 class="mt-5"><?php echo $translate[10];?></h1>

    <head>
        <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.css">
	</head>

    <?php

		$sql = "SELECT pr.`title`, pr.`points`, pr.`creationDate`, pr.ID FROM `tb_stao` AS pr
                LEFT JOIN tb_user AS us ON us.ID = pr.tb_user_ID
                WHERE us.deleted IS NULL ORDER BY pr.`creationDate` DESC LIMIT 400;";

        $entryList = "";

        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {

            $i = 1;

            while($row = $result->fetch_assoc()) {

                $dateSet =  date("d.m.Y", strtotime($row['creationDate']));

                $sql2 = "SELECT us.firstname, us.lastname FROM `tb_stao` AS pr
                LEFT JOIN tb_user AS us ON pr.`tb_user_ID` = us.ID WHERE pr.ID = " . $row['ID'];
                $result2 = $mysqli->query($sql2);
                $row2 = $result2->fetch_assoc();

                $listEntry = '
                <tr>
                    <td><button entryID="'. $row['ID'] .'"  entryPoints="'. $row['points'] .'" entryLL="'. $row2['firstname'] .' '. $row2['lastname'] .'" type="button" class="btn btn-warning checkEntry" style="padding-bottom: 0px; padding-top: 0px;"><b>!</b></button></td>
                    <th scope="row">'.$i.'</th>
                    <td>'. $row['title'] .'</td>
                    <td>'. $row['points'] .' %</td>
                    <td>'. $row2['firstname'] .' '. $row2['lastname'] .'</td>
                    <td>'. $dateSet .' </td>
                </tr>';

                $entryList = $entryList . $listEntry;
                $i = $i + 1;

            }
        } else {
            $entryList = $translate[105];
        }

    ?>

    <div id="loadingTable" style="display: none; text-align:center; width: 100%">
        <img class="img-responsive" src="img/loading2_big.svg"/>
    </div>

    <table class="table" id="dtmake" style="display: none;">
        <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th><?php echo $translate[66];?></th>
                <th><?php echo $translate[67];?> (<?php echo $translate[109];?>)</th>
                <th><?php echo $translate[20];?></th>
                <th><?php echo $translate[22];?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                if($entryList){
                    echo $entryList;
                } else {
                    echo "<tr><td colspan='7' align='center'>".$translate[87]."</td></tr>";
                }
            ?>
        </tbody>
    </table>

	<div class="alert alert-success" id="checkedNotif" style="display: none; margin-bottom: 0px;"></div><br/>
    <div id="checkEntryForm" style="display: none;">
        <hr/>
        <h3><?php echo $translate[29];?></h3>
        <div class="row">
            <div class="col-lg-6">
                <label for="fcheckEntryPoints"><?php echo $translate[19];?></label>
                <input class="form-control" type="text" id="fcheckEntryPoints" value="" disabled/>
            </div>
            <div class="col-lg-6">
                <label for="fcheckEntryLL"><?php echo $translate[20];?></label>
                <input class="form-control" type="text" id="fcheckEntryLL" value="" disabled/>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-12">
                <label for="fcheckEntryReason"><?php echo $translate[30];?></label>
                <textarea class="form-control" id="fcheckEntryReason"></textarea><br/>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <button type="button" entryID="" id="fsend" class="btn btn-primary"><?php echo $translate[31];?></button>
                <?php if($session_usergroup == 1){echo '<button type="button" entryID="" id="fsendAndDelete" class="btn btn-danger">'.$translate[32].'</button>';} ?>
            </div>
            <div class="col-lg-6">
                <button type="button" style="float: right;" id="finterrupt" class="btn btn-secondary"><?php echo $translate[33];?></button>
            </div>
        </div>
        <br/>
        <div class="row">
            <div class="col-lg-12">
                <p><?php echo $translate[34];?>.</p>
            </div>
        </div>
    </div>
    <hr/>

    <div class="row">
        <div class="col-12 card" style="padding-top: 15px;">
            <h2><?php echo $translate[68];?></h2>
            <div class="row">
                <div class="col-lg-4">
                    <label for="calcProzPoints"><?php echo $translate[69];?></label>
                    <input class="form-control" type="number" id="calcProzPoints"/>
                </div>
                <div class="col-lg-4">
                    <label for="calcProzPoints"><?php echo $translate[70];?></label>
                    <input class="form-control" type="number" id="calcProzMaxPoints" value=""/>
                </div>
                <div class="col-lg-2">
                    <br/>
                    <button type="button" class="btn" id="calcProz"><?php echo $translate[71];?></button>
                </div>
                <div class="col-lg-2">
                    <br/>
                    <span id="calcProzResult"></span>
                </div>
            </div>
            <br/>
        </div>
    </div>


    <script type="text/javascript" src="modul/stao/stao.min.js"></script>
    <script type="text/javascript" src="js/defaultTableActions.min.js"></script>
    <script type="text/javascript" src="js/datatablesInit.js"></script>

<?php elseif($session_usergroup == 4) : ?>

    <h1 class="mt-5"><?php echo $translate[10];?></h1>

    <?php

        $sql = "SELECT pres.`title`, pres.`points`, pres.`creationDate`, sem.semester, pres.ID FROM `tb_stao` AS pres
                INNER JOIN tb_semester AS sem ON sem.ID = pres.tb_semester_ID
                WHERE `tb_user_ID`= $session_userid;";

        $entryList = "";

        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {

            $i = 1;

            while($row = $result->fetch_assoc()) {

                $dateSet =  date("d.m.Y", strtotime($row['creationDate']));

                $listEntry = '
                <tr>
                    <th scope="row">'.$i.'</th>
                    <td>'. $row['title'] .'</td>
                    <td>'. $row['points'] .' %</td>
                    <td>'. $row['semester'] .'</td>
                    <td>'. $dateSet .' </td>
                </tr>';

                $entryList = $entryList . $listEntry;
                $i = $i + 1;

            }
        }

        $semList = "";
        $semSql = "SELECT ID, semester FROM `tb_semester` WHERE tb_group_ID = $session_usergroup";
        $semResult = $mysqli->query($semSql);
        if ($semResult->num_rows > 0) {
            while($semRow = $semResult->fetch_assoc()) {
                $semList = $semList . "<option value='". $semRow['ID'] ."'>". $semRow['semester'] ."</option>";
            }
        } else {
            $semList = "<option>Keine Semester vorhanden.</option>";
        }

    ?>

    <p><?php echo $translate[35];?>.</p>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo $translate[66];?></th>
                <th><?php echo $translate[67];?> (<?php echo $translate[109];?>)</th>
                <th><?php echo $translate[38];?></th>
                <th><?php echo $translate[22];?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($entryList){echo $entryList;} else {echo "Du hast bisher nichts eingetragen.";} ?>
            <tr id="newEntry">
                <th scope="row" style="padding-top: 20px;">#</th>
                <td><input class="form-control" type="text" id="fTitle"/></td>
                <td><input class="form-control" type="number" id="fPoints"/></td>
                <td>
                    <select class="form-control" id="fSem">
                        <option></option>
                        <?php echo $semList; ?>
                    </select>
                </td>
                <td style="padding-top: 20px;"><?php echo date("d.m.Y"); ?></td>
            </tr>
            <tr>
                <td colspan="6" align="center">
                    <button id="addNewEntryButton" type="button" class="btn btn-primary"><?php echo $translate[39];?></button>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-12 card" style="padding-top: 15px;">
            <h2><?php echo $translate[68];?></h2>
            <div class="row">
                <div class="col-lg-4">
                    <label for="calcProzPoints"><?php echo $translate[69];?></label>
                    <input class="form-control" type="number" id="calcProzPoints"/>
                </div>
                <div class="col-lg-4">
                    <label for="calcProzPoints"><?php echo $translate[70];?></label>
                    <input class="form-control" type="number" id="calcProzMaxPoints" value=""/>
                </div>
                <div class="col-lg-2">
                    <br/>
                    <button type="button" class="btn" id="calcProz"><?php echo $translate[71];?></button>
                </div>
                <div class="col-lg-2">
                    <br/>
                    <span id="calcProzResult"></span>
                </div>
            </div>
            <br/>
        </div>
    </div>


    <script type="text/javascript" src="modul/stao/stao.min.js"></script>
    <script type="text/javascript" src="js/defaultTableActions.min.js"></script>

<?php else : ?>

    <br/><br/>

    <div class='alert alert-danger'>
        <strong><?php echo $translate[95];?> </strong> <?php echo $translate[94];?>.
    </div>

<?php endif; ?>
