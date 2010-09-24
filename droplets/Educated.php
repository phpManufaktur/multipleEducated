//:Puts a multipleEducated Dialog to your page
//:usage: [[Educated?group_id=1]] - specify a group_id to select questions only of this group
$educated = "multipleEducated is not installed!";
if (!isset($group_id)) $group_id = -1;
if (file_exists(WB_PATH.'/modules/educated/class.frontend.php')) {
  require_once(WB_PATH.'/modules/educated/class.frontend.php');
  $educated = new multipleEducated($group_id);
  ob_start();
    $educated->action();
    $educated = ob_get_contents();
  ob_end_clean();}
return $educated;