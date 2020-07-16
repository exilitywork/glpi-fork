<?php

/**
 * Install the plugin
 *
 * @return boolean
 */
function plugin_unreadmessages_install() {
    global $DB;

    if (!$DB->tableExists('glpi_plugin_unreadmessages')) {
        $create_table_query = "
            CREATE TABLE IF NOT EXISTS `glpi_plugin_unreadmessages`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `tickets_id` INT(11) NOT NULL,
                `users_id` INT(11) NOT NULL,
		`reading_date` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY (`tickets_id`),
		KEY (`users_id`)
            ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci;
        ";
        $DB->query($create_table_query) or die($DB->error());
    }

    return true;
}

/**
 * Uninstall the plugin
 *
 * @return boolean
 */
function plugin_unreadmessages_uninstall() {
    global $DB;

    $drop_table_query = "DROP TABLE IF EXISTS `glpi_plugin_unreadmessages`";

    return $DB->query($drop_table_query) or die($DB->error());
}

