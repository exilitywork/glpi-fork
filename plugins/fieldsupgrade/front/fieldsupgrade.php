<?php
    include ('../../../inc/includes.php');

    if (isset($_REQUEST['id'])) {
        if (in_array($_SESSION['glpiactiveprofile']['id'], array(4, 13)) && isset($_REQUEST['pay'])) {
            PluginFieldsupgradeFieldsupgrade::updateField($_REQUEST['id'], $_REQUEST['pay']);
        }
        Html::redirect($CFG_GLPI["root_doc"]."/front/ticket.form.php?id=".$_REQUEST['id']);
    } else {
        Html::redirect($CFG_GLPI["root_doc"]);
    }
?>