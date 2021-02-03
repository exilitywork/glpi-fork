<?php

class PluginFieldsupgradeFieldsupgrade extends CommonDBTM {
    static $rightname = 'config';

    /**
     * Modify needed fields of plugin Fields
     *
     * @param array $params [item, options]
     */
    static function post_show_tab($params) {
        $item       = $params['item'];
        $options    = $params['options'];

        // Проверка права доступа к редактированию поля и нужного itemtype
        if (in_array($_SESSION['glpiactiveprofile']['id'], array(4, 13)) && ($options['itemtype'] == 'PluginFieldsContainer')) {    
            $id         = $options['id'];

            echo "<script>
                var tableRow = $('tr:contains(Состояние оплаты)');
                var payField = tableRow.find('td:last');
                td = $('<td>').appendTo(tableRow);
                $(\"<button id='paybtn' type='button' value='Изменить' class='submit' onclick='changeField(payField)'>Изменить</button>\").appendTo(td);

                function changeField(payField) {
                    var cur_pay = payField.text();
                    payField.text('');
                    var url_pay = 'location.href=\"/plugins/fieldsupgrade/front/fieldsupgrade.php?id=' + $id + '&pay=' + cur_pay + '\";';
                    payField.append(\"<input id='payment' type='text' oninput='modUrlOnInput();' value='\" + cur_pay + \"'>\");
                    $('#paybtn').attr('onclick', url_pay);
                    $('#paybtn').text('Применить');
                }

                function modUrlOnInput() {
                    $('#paybtn').attr('onclick', 'location.href=\"/plugins/fieldsupgrade/front/fieldsupgrade.php?id=' + $id + '&pay=' + $('#payment').val() + '\";');
                }
            </script>";
        }
    }

    /**
     * Insert or update field in DB
     *
     * @param string $id    Item ID
     * @param string $pay   Field value
     */
    static function updateField($id, $pay) {
        global $DB;
        
        // Если запись в таблице есть, то обновляем ее. Иначе - создаем.
        if (count($DB->request(['FROM' => 'glpi_plugin_fields_ticketnotesfortickets', 'WHERE' => ['items_id' => $id, 'plugin_fields_containers_id' => 13]]))) {
            $DB->update(
                'glpi_plugin_fields_ticketnotesfortickets', [
                    'field7'                    => $pay,
                    'itemtype'                      => 'Ticket'
                ], [
                    'items_id'                      => $id,
                    'plugin_fields_containers_id'   => 13
                ]
            ); 
        } else {
            $DB->insert(
                'glpi_plugin_fields_ticketnotesfortickets', [
                    'items_id'                      => $id,
                    'itemtype'                      => 'Ticket',
                    'plugin_fields_containers_id'   => 13,
                    'field7'                    => $pay
                ]
            );
        }
    }
}
?>