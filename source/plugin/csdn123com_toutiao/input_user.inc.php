<?php
if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
function insertUserAli($uid) {
    if (is_numeric($uid) == false) {
        return false;
    }
    $chkUid2 = DB::fetch_first('SELECT * FROM ' . DB::table('csdn123toutiao_reguser') . ' WHERE uid=' . $uid);
    if (count($chkUid2) == 0) {
        $chkUid = getuserbyuid($uid, 1);
        if (count($chkUid) > 1) {
            $userArr = array();
            $userArr['uid'] = $uid;
            $userArr['username'] = $chkUid['username'];
            $userArr['username_pwd'] = 'XXXXXXXXXX';
            DB::insert('csdn123toutiao_reguser', $userArr);
        }
    }
}
if ($_GET['formhash'] == FORMHASH && empty($_GET['inputuser']) == false && $_GET['inputuser'] == 'yes') {
    if (empty($_GET['add_uids']) == false) {
        $add_uids = $_GET['add_uids'];
        if (strpos($add_uids, ',') == false) {
            insertUserAli($add_uids);
        } else {
            $uid_arr = explode(',', $add_uids);
            foreach ($uid_arr as $uidValue) {
                insertUserAli($uidValue);
            }
        }
    }
    $tiaoUrl = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=admin_user';
    cpmsg("csdn123com_toutiao:csdn123_add_reguser_ok", $tiaoUrl, "succeed");
}
$server_url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=csdn123com_toutiao&pmod=input_user';
include template('csdn123com_toutiao:input_user');
