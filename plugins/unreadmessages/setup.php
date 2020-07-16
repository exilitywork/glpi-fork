<?php

define('PLUGIN_UNREADMESSAGES_MIN_GLPI_VERSION', '9.4');
define('PLUGIN_UNREADMESSAGES_NAMESPACE', 'groupcategory');

/**
 * Plugin description
 *
 * @return boolean
 */
function plugin_version_unreadmessages() {
    return [
      'name' => 'Unread Messages Table',
      'version' => '1.0',
      'author' => 'BELWEST - Kapeshko Oleg',
      'homepage' => '',
      'license' => 'local',
      'minGlpiVersion' => PLUGIN_UNREADMESSAGES_MIN_GLPI_VERSION,
    ];
}

/**
 * Initialize plugin
 *
 * @return boolean
 */
function plugin_init_unreadmessages() {
    if (Session::getLoginUserID()) {
        global $PLUGIN_HOOKS;
        $PLUGIN_HOOKS['csrf_compliant'][PLUGIN_UNREADMESSAGES_NAMESPACE] = true;
/* TODO
        //$PLUGIN_HOOKS['post_show_item'][PLUGIN_GROUPCATEGORY_NAMESPACE] = ['PluginGroupcategoryGroupcategory', 'post_show_item'];
        $PLUGIN_HOOKS['post_item_form'][PLUGIN_GROUPCATEGORY_NAMESPACE] = ['PluginGroupcategoryGroupcategory', 'post_item_form'];
        $PLUGIN_HOOKS['pre_item_update'][PLUGIN_GROUPCATEGORY_NAMESPACE] = [
          'Group' => 'plugin_groupcategory_group_update',
        ];
*/
    }
}

/**
 * Check if plugin prerequisites are met
 *
 * @return boolean
 */
function plugin_unreadmessages_check_prerequisites() {
    $prerequisites_check_ok = false;

    try {
        if (version_compare(GLPI_VERSION, PLUGIN_UNREADMESSAGES_MIN_GLPI_VERSION, '<')) {
            throw new Exception('This plugin requires GLPI >= ' . PLUGIN_UNREADMESSAGES_MIN_GLPI_VERSION);
        }

        $prerequisites_check_ok = true;
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    return $prerequisites_check_ok;
}

/**
 * Check if config is compatible with plugin
 *
 * @return boolean
 */
function plugin_unreadmessages_check_config() {
    // nothing to do
    return true;
}
