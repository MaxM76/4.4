<?php

if (isset($_GET['current_table'])) {
    $currentTable = (string) $_GET['current_table'];
} else {
    $currentTable = '';
}

require_once __DIR__ . '/connect_db.php';

$query = 'SHOW TABLES';
$cat = $pdo->query($query);

try {
    $dbTables = $cat->fetchAll(PDO::FETCH_NUM);
} catch (PDOException $e) {
    echo 'Ошибка выполнения запроса: ' . $e->getMessage();
    echo '<br>';
}

if (isset($_GET['action'])) {
    $action = (string) $_GET['action'];
} else {
    $action = 'show';
}

switch ($action) {
    case 'table_info':
        echo 'table_info';
        echo '<br>';
        echo '$currentTable = ' . $currentTable;
        echo '<br>';
        if (isset($_GET['selected_table'])) {
            $currentTable = (string)$_GET['selected_table'];
        }
         /*   try {
                $query = "SHOW COLUMNS FROM $currentTable";

                $cat = $pdo->prepare($query);
                $cat->execute();
                $tableFields = $cat->fetchAll(PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
                echo '<br>';
            }
        } else {
            $action = 'show';
        }*/
        break;
    case 'create_table':
        echo 'create_table';
        echo '<br>';
        echo '$currentTable = ' . $currentTable;
        echo '<br>';
        if (isset($_GET['table_name'])) {
            $nameNewTable = (string) $_GET['table_name'];
            try {
                $query =  "CREATE TABLE $nameNewTable (`id` INT NOT NULL AUTO_INCREMENT,
                           PRIMARY KEY(`id`))
                            ENGINE=InnoDB, DEFAULT CHARACTER SET=utf8";
                $cat = $pdo->prepare($query);
                $cat->execute([':tableName' => $nameNewTable]);
                $currentTable = $nameNewTable;
            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
                echo '<br>';
            }
        }
        break;
    case 'add_field':
        echo 'add_field';
        echo '<br>';
        echo '$currentTable = ' . $currentTable;
        echo '<br>';
        if (isset($_GET['field_name']) && ($currentTable != '')) {
            $nameNewField = (string) $_GET['field_name'];
            switch ((string) $_GET['data_type']) {
                case 'int' :
                    $typeNewField = 'INT';
                    break;
                case 'float' :
                    $typeNewField = 'FLOAT';
                    break;
                case 'timestamp' :
                    $typeNewField = 'TIMESTAMP';
                    break;
                case 'text' :
                    $typeNewField = 'TEXT';
                    break;
            }

            try {
                $query = "ALTER TABLE $currentTable ADD :nameNewField $typeNewField";
                // ALTER TABLE `new` ADD `name` INT NOT NULL AFTER `id`;

                $cat = $pdo->prepare($query);
                $cat->execute([':nameNewField' => $nameNewField]);

            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
                echo '<br>';
            }
        }
        break;
    case 'delete_field':
        echo 'delete_field';
        echo '<br>';
        echo '$currentTable = ' . $currentTable;
        echo '<br>';
        if (isset($_GET['selected_field']) && ($currentTable != '')) {
            $nameSelectedField = (string) $_GET['selected_field'];
            try {
                $query = "ALTER TABLE $currentTable DROP COLUMN :nameSelectedField";

                $cat = $pdo->prepare($query);
                $cat->execute([':nameSelectedField' => $nameSelectedField]);

            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
                echo '<br>';
            }
        }
        break;

    case 'show':

        break;
}

if ($currentTable != '') {
    try {
        $query = "SHOW COLUMNS FROM $currentTable";

        $cat = $pdo->prepare($query);
        $cat->execute();
        $tableFields = $cat->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo 'Ошибка выполнения запроса: ' . $e->getMessage();
        echo '<br>';
    }
}

echo '///////////////////////////////////////////////';
echo '<br>';
echo '$currentTable = ' . $currentTable;
echo '<br>';
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <title>Домашняя работа 4.4</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body>
    <h1>База данных <?= $nameDB ?></h1>

    <form method="GET">
      <fieldset>
        <label>Введите имя новой таблицы: </label>
        <input type="text" name="table_name" placeholder="Имя новой таблицы" value="">
        <button name="action" value="create_table">Создать таблицу</button>
        <br>
      </fieldset>

      <fieldset>
        <legend>Таблицы базы данных <?= $nameDB ?></legend>
        <select name="selected_table" size="<?= count($dbTables) ?>">
<?php
foreach ($dbTables as $dbTable) : ?>
          <option value="<?=$dbTable[0]?>"><?=$dbTable[0]?></option>
<?php
endforeach;
?>
        </select>
        <input type="hidden" name="current_table" value="<?= $currentTable ?>">
        <br>
        <label>Выберите таблицу</label>
        <button name="action" value="table_info">Показать информацию о таблице</button>
      </fieldset>

      <fieldset <?= ($currentTable =='')?'hidden':'' ?>>
        <legend>Структура таблицы <?= $currentTable ?></legend>
        <table border="1">
          <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Null</th>
            <th>Key</th>
            <th>Default</th>
            <th>Extra</th>
          </tr>
<?php
if ($currentTable !='') {
    foreach ($tableFields as $tableField) : ?>
          <tr>
<?php
        foreach ($tableField as $fieldCharacteristic) : ?>
            <td><?=$fieldCharacteristic?></td>
<?php
        endforeach;
?>
          </tr>
<?php
    endforeach;
}
?>
        </table>
      </fieldset>

      <fieldset <?= ($currentTable =='')?'hidden':'' ?>>
        <legend>Добавление поля</legend>
        <label>Введите имя поля: </label>
        <input type="text" name="field_name" placeholder="Имя поля" value=""><br>
        <label>Выберите тип данных поля</label>
        <select name="data_type">
          <option value="int">INT</option>
          <option value="float">FLOAT</option>
          <option value="timestamp">TIMESTAMP</option>
          <option value="text">TEXT</option>
        </select>
        <br>
        <button name="action" value="add_field">Добавить поле</button>

      </fieldset>
      <fieldset <?= ($currentTable =='')?'hidden':'' ?>>
        <legend>Изменение поля</legend>
        <label>Выберите поле: </label>
        <select name="selected_field" size="<?= count($tableFields) ?>">
<?php
foreach ($tableFields as $tableField) : ?>
          <option value="<?=$tableField['Field']?>"><?=$tableField['Field']?></option>
<?php endforeach;?>
        </select>

        <label>Введите новое имя поля: </label>
        <input type="text" name="field_name" placeholder="Новое имя" value=""><br>
        <label>Выберите тип данных поля</label>
        <select name="data_type">
          <option value="int">INT</option>
          <option value="float">FLOAT</option>
          <option value="timestamp">TIMESTAMP</option>
          <option value="text">TEXT</option>
        </select>
                  
        <br>
        <button name="action" value="change_field">Изменить</button>
        <button name="action" value="delete_field">Удалить</button>
      </fieldset>


    </form>
  </body>
</html>