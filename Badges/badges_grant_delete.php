<?php
/*
Gibbon, Flexible & Open School System
Copyright (C) 2010, Ross Parker

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

use Gibbon\Forms\Prefab\DeleteForm;

//Module includes
include './modules/'.$_SESSION[$guid]['module'].'/moduleFunctions.php';

if (isActionAccessible($guid, $connection2, '/modules/Badges/badges_grant_delete.php') == false) {
    //Acess denied
    echo "<div class='error'>";
    echo __('You do not have access to this action.');
    echo '</div>';
} else {
     //Proceed!
    if (isset($_GET['return'])) {
        returnProcess($guid, $_GET['return'], null, null);
    }

    //Check if school year specified
    $badgesBadgeStudentID = $_GET['badgesBadgeStudentID'];
    $gibbonSchoolYearID = $_GET['gibbonSchoolYearID'];
    if ($badgesBadgeStudentID == '') { echo "<div class='error'>";
        echo __('You have not specified one or more required parameters.');
        echo '</div>';
    } else {
        try {
            $data = array('badgesBadgeStudentID' => $badgesBadgeStudentID);
            $sql = 'SELECT * FROM badgesBadgeStudent WHERE badgesBadgeStudentID=:badgesBadgeStudentID';
            $result = $connection2->prepare($sql);
            $result->execute($data);
        } catch (PDOException $e) {
            echo "<div class='error'>".$e->getMessage().'</div>';
        }

        if ($result->rowCount() != 1) {
            echo "<div class='error'>";
            echo __('The selected record does not exist, or you do not have access to it.');
            echo '</div>';
        } else {
            //Let's go!
            $row = $result->fetch();

            if ($_GET['gibbonPersonID2'] != '' or $_GET['badgesBadgeID2'] != '') {
                echo "<div class='linkTop'>";
                echo "<a href='".$_SESSION[$guid]['absoluteURL'].'/index.php?q=/modules/Badges/badges_grant.php&gibbonPersonID2='.$_GET['gibbonPersonID2'].'&badgesBadgeID2='.$_GET['badgesBadgeID2']."&gibbonSchoolYearID=$gibbonSchoolYearID"."'>".__('Back to Search Results').'</a>';
                echo '</div>';
            }

            $form = DeleteForm::createForm($_SESSION[$guid]['absoluteURL'].'/modules/'.$_SESSION[$guid]['module']."/badges_grant_deleteProcess.php?badgesBadgeStudentID=$badgesBadgeStudentID&gibbonPersonID2=".$_GET['gibbonPersonID2'].'&badgesBadgeID2='.$_GET['badgesBadgeID2']."&gibbonSchoolYearID=$gibbonSchoolYearID");
            echo $form->getOutput();
        }
    }
}
?>
