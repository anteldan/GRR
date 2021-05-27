<?php
$grr_script_name = 'param_affichage_room.php';
include_once('../include/connect.inc.php');
include_once('../include/config.inc.php');
include_once('../include/misc.inc.php');
include_once('../include/functions.inc.php');
require_once('../include/' . $dbsys . '.inc.php');
require_once('../include/session.inc.php');
include_once('../include/settings.class.php');

if (!Settings::load())
    die('Erreur chargement settings');
$desactive_VerifNomPrenomUser = 'y';
if (!grr_resumeSession()) {
    header('Location: ../logout.php?auto=1&url=$url');
    die();
};
// Definition_ressource_domaine_site();
$day = isset($_POST['day']) ? $_POST['day'] : (isset($_GET['day']) ? $_GET['day'] : date('d'));
$month = isset($_POST['month']) ? $_POST['month'] : (isset($_GET['month']) ? $_GET['month'] : date('m'));
$year = isset($_POST['year']) ? $_POST['year'] : (isset($_GET['year']) ? $_GET['year'] : date('Y'));
include_once('../include/language.inc.php');
//include "../include/resume_session.php";
$back = (isset($_SERVER['HTTP_REFERER'])) ? htmlspecialchars_decode($_SERVER['HTTP_REFERER'], ENT_QUOTES) : page_accueil();
$valid = isset($_POST['valid']) ? $_POST['valid'] : NULL;
// valeurs par défaut pour le reset
$reset_site = Settings::get('default_site');
$reset_area = Settings::get('default_area');
$reset_room = Settings::get('default_room');
$msg = '';

$use_prototype = 'y';
// début du code HTML
start_page_w_header($day, $month, $year, $type = "with_session");
echo "\n    <!-- Repere " . $grr_script_name . " -->\n";
if (Settings::get("module_multisite") == "Oui")
    $use_site = 'y';
else
    $use_site = 'n';


//si le formulaire est validé 
if ($valid) {
    $sql = "DELETE FROM " . TABLE_PREFIX . "_not_show_room WHERE login = '" . getUserName() . "'";
    grr_sql_query($sql);
    $key = array_keys($_POST);
    for ($i = 0; $i < count($key) - 1; $i++) {
        $sql = "INSERT INTO " . TABLE_PREFIX . "_not_show_room VALUES ('" . getUserName() . "', " . $key[$i] . ")";
        grr_sql_query($sql);
    }
}

// données utilisateur
$sql = "SELECT id, area_name FROM " . TABLE_PREFIX . "_area WHERE access <> 'r'
        UNION 
        SELECT id, area_name FROM " . TABLE_PREFIX . "_area, " . TABLE_PREFIX . "_j_user_area WHERE id_area = id AND access = 'r' AND login ='" . getUserName() . "'";

$res = grr_sql_query($sql);
echo ('<div class="container">
<form id="param_affichage" action="param_affichage_room.php" method="post">
');
if ($res) {
    //sélection des données enregisté
    $sql = "SELECT id_room FROM " . TABLE_PREFIX . "_not_show_room WHERE login = '" . getUserName() . "'";
    $not_show_room = array();
    $res3 = grr_sql_query($sql);
    if ($res3) {
        for ($j = 0; ($row3 = grr_sql_row($res3, $j)); $j++) {
            array_push($not_show_room, $row3[0]);
        }
    }
    grr_sql_free($res3);
    for ($i = 0; ($row = grr_sql_row($res, $i)); $i++) {
        echo "<h2>" . $row[1] . "</h2>
        <table>";
        $sql = "SELECT id, room_name FROM " . TABLE_PREFIX . "_room WHERE area_id = '" . $row[0] . "'";
        $res2 = grr_sql_query($sql);
        if ($res2) {
            for ($j = 0; ($row2 = grr_sql_row($res2, $j)); $j++) {
                echo ('<tr><td>' . $row2[1] . '</td><td><input type="checkbox" name="' . $row2[0] . '" ');
                if (in_array($row2[0], $not_show_room)) {
                    echo 'checked';
                }
                echo ('></td></tr>');
            }
        }
        grr_sql_free($res2);
        echo "</table>";
    }
}
grr_sql_free($res);

echo '
<div id="fixe">
    <input type="hidden" name="valid" value="yes" />
    <input class="btn btn-primary" type="submit" value="' . get_vocab('save') . '" />
    <input class="btn btn-primary" type="reset" value="' . get_vocab('reset') . '" />
</div>
</form>
</body>
</html>';
