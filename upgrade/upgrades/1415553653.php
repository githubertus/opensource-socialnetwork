<?php
/**
 *    OpenSource-SocialNetwork
 *
 * @package   (Informatikon.com).ossn
 * @author    OSSN Core Team <info@opensource-socialnetwork.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://opensource-socialnetwork.com/licence
 * @link      http://www.opensource-socialnetwork.com/licence
 */

$component = new OssnComponents;
$database = new OssnDatabase;
/**
 * Add OssnPoke Component
 *
 * @access private
 */
$component->newCom('OssnPoke');

/**
 * Delete invalid notification from system (group like)
 *
 * @access private
 */
$delete['from'] = 'ossn_notifications';
$delete['wheres'] = array("type='like:post:group:wall'");
$database->delete($delete);

/**
 * Update processed updates in database so user cannot upgrade again and again.
 *
 * @access private
 */

$upgrade_json = array_merge(ossn_get_upgraded_files(), $upgrades);
$upgrade_json = json_encode($upgrade_json);

$update['table'] = 'ossn_site_settings';
$update['names'] = array('value');
$update['values'] = array($upgrade_json);
$update['wheres'] = array("name='upgrades'");

if ($database->update($update)) {
    ossn_trigger_message(ossn_print('upgrade:success'), 'success');
    redirect('administrator');
} else {
    ossn_trigger_message(ossn_print('upgrade:failed'), 'error');
    redirect('administrator');
}