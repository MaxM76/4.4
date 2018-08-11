<?php

$action = 'show';


require_once __DIR__ . '/connect_db.php';

if (isset($_GET['action'])) {
    $action = (string) $_GET['action'];
} else {
    $action = 'show';
}

switch ($action) {
    case 'table_info':
        if (isset($_GET['selected_table'])) {
            $nameSelectedTable = (string) $_GET['selected_table'];
            try {
                $query = "SHOW COLUMNS FROM books";

                $cat = $pdo->prepare($query);
                $cat->execute([':nameSelectedTable' => $nameSelectedTable]);
                $tableFields = $cat->fetchAll(PDO::FETCH_ASSOC);
                var_dump($tableFields);
                echo '<pre>';
                print_r($tableFields);
                echo '</pre>';
            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
            }
        } else {
            $action = 'show';
        }
        break;
    case 'create_table':
        if (isset($_GET['table_name'])) {
            $nameNewTable = (string) $_GET['table_name'];
            try {
                $query =  "CREATE TABLE new (`id` INT NOT NULL AUTO_INCREMENT,
                           PRIMARY KEY(`id`))
                            ENGINE=InnoDB, DEFAULT CHARACTER SET=utf8";
                $cat = $pdo->prepare($query);
                $cat->execute([':tableName' => $nameNewTable]);
            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
            }
        } else {
            $action = 'show';
        }
        break;
    case 'add_field':
        if (isset($_GET['field_name'])) {
            $nameNewField = (string) $_GET['field_name'];
            $typeNewField = (string) $_GET['data_type'];
            switch ($typeNewField) {
                case 'int' :
                    $sqlDataType = 'INT';
                    break;
                case 'float' :
                    $sqlDataType = 'FLOAT';
                    break;
                case 'timestamp' :
                    $sqlDataType = 'TIMESTAMP';
                    break;
                case 'text' :
                    $sqlDataType = 'TEXT';
                    break;
            }

            try {
                $query = "ALTER TABLE :nameSelectedTable ADD :nameNewField :typeNewField";

                $cat = $pdo->prepare($query);
                $cat->execute([':nameSelectedTable' => $nameSelectedTable, ':nameNewField' => $typeNewField]);

            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
            }
        } else {
            $action = 'show';
        }
        break;
    case 'delete_field':
        if (isset($_GET['selected_field'])) {
            $nameSelectedField = (string) $_GET['selected_field'];
            try {
                $query = "ALTER TABLE :nameSelectedTable DROP COLUMN :nameSelectedField";

                $cat = $pdo->prepare($query);
                $cat->execute([':nameSelectedTable' => $nameSelectedTable, ':nameSelectedField' => $nameSelectedField]);

            } catch (PDOException $e) {
                echo 'Ошибка выполнения запроса: ' . $e->getMessage();
            }
        } else {
            $action = 'show';
        }
        break;

    case 'show':

        break;
}



$query =  'SHOW TABLES';
$cat = $pdo->query($query);

try {
    $dbTables = $cat->fetchAll(PDO::FETCH_NUM);
} catch (PDOException $e) {
    echo 'Ошибка выполнения запроса: ' . $e->getMessage();
}


?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <title>Домашняя работа 4.4</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

  <body>
    <p>База данных <?= $nameDB ?></p>

    <form method="GET">
      <fieldset>
        <label>Введите имя новой таблицы: </label>
        <input type="text" name="table_name" placeholder="Имя новой таблицы" value="">
        <button name="action" value="create_table">Создать таблицу</button>
        <br>
      </fieldset>

      <fieldset>
        <legend>Таблицы базы данных</legend>
        <select name="selected_table" size="<?= count($dbTables) ?>">
        <?php
        foreach ($dbTables as $dbTable) : ?>
          <option value="<?=$dbTable[0]?>"><?=$dbTable[0]?></option>
        <?php endforeach;?>
        </select>

        <label>Выберите таблицу</label>
        <button name="action" value="table_info">Показать информацию о таблице</button>
      </fieldset>

      <fieldset>
        <legend>Структура таблицы <?= $nameSelectedTable ?></legend>
        <table>
          <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Null</th>
            <th>Key</th>
            <th>Default</th>
            <th>Extra</th>
          </tr>
          <?php
          foreach ($tableFields as $tableField) : ?>
          <tr>
          <?php
          foreach ($tableField as $fieldCharacteristic) : ?>
            <td><?=$fieldCharacteristic?></td>
          <?php endforeach;?>
          </tr>
          <?php endforeach;?>
        </table>
      </fieldset>


      <fieldset>
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
      <fieldset>
        <legend>Удаление поля</legend>
        <select name="selected_field" size="<?= count($tableFields) ?>">
          <?php
          foreach ($tableFields as $tableField) : ?>
          <option value="<?=$tableField['Field']?>"><?=$tableField['Field']?></option>
          <?php endforeach;?>
        </select>
        <!-- <input type="submit" name="delete_field" value="Удалить поле"> -->
        <button name="action" value="delete_field">Удалить</button>
      </fieldset>


    </form>
  </body>
</html>