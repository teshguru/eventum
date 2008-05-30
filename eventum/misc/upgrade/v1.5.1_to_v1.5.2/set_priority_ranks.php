<?php
require_once(dirname(__FILE__) . "/../../../init.php");
require_once(APP_INC_PATH . "db_access.php");

$sql = "SELECT
            pri_id,
            pri_prj_id
        FROM
            " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_priority
        WHERE
            pri_rank < 1
        ORDER BY
            pri_prj_id,
            pri_title";
$res = $GLOBALS["db_api"]->dbh->getAssoc($sql);
if (PEAR::isError($res)) {
	echo 'ERROR: ', $res->getMessage(), ': ', $res->getDebugInfo(), "\n";
    exit(1);
}
    
$rank = 1;
$last_prj_id = 0;
foreach ($res as $id => $prj_id) {
    if ($prj_id != $last_prj_id) {
        $rank = 1;
    }
    $last_prj_id = $prj_id;
    $sql = "UPDATE
                " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_priority
            SET
                pri_rank = $rank
            WHERE
                pri_id = $id";
    $res = $GLOBALS["db_api"]->dbh->query($sql);
    if (PEAR::isError($res)) {
		echo 'ERROR: ', $res->getMessage(), ': ', $res->getDebugInfo(), "\n";
        exit(1);
    }
    $rank++;
}

?>
done
