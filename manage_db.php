<?php

require_once __DIR__ . '/connect_db.php';

if (isset($_GET['action'])) {
    $action = (string) $_GET['action'];
} else {
    $action = 'show';
}

switch ($action) {
    case 'table_info':

        break;
    case 'create_table':

        break;
    case 'add_field':

        break;
    case 'delete_field':

        break;
    case 'show':

        break;
}



$query =  'SHOW TABLES';
$cat = $pdo->query($query);

try {
    $tables = $cat->fetchAll();
} catch (PDOException $e) {
    echo 'Ошибка выполнения запроса: ' . $e->getMessage();
}



$query =  'CREATE DATABASE db_name';
$cat = $pdo->query($query);

try {

} catch (PDOException $e) {
    echo 'Ошибка выполнения запроса: ' . $e->getMessage();
}


try {
    $query =  'CREATE DATABASE :dbName';
    $cat = $pdo->prepare($query);
    $cat->execute([:dbName]=>$dbName);

} catch (PDOException $e) {
    echo 'Ошибка выполнения запроса: ' . $e->getMessage();
}



try {
    $query =  'CREATE DATABASE :dbName';
    $cat = $pdo->prepare($query);
    $cat->execute([:dbName]=>$dbName);

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
    <p>База данных</p>


    <form method="GET">
      <fieldset>
        <legend>Таблицы базы данных</legend>
        <select size="<?= count($dbTables) ?> ">
            <?php
            foreach ($dbTables as $dbTable) {
                echo "<option>$dbTable</option>";
            }
            ?>
        </select>




        <label>Выберите таблицу</label>
        <button name="action" value="table_info">Показать информацию о таблице</button>
        <label>Введите имя новой таблицы: </label>
        <input type="text" name="_name" placeholder="Имя новой таблицы" value="">
        <button name="action" value="create_table">Создать таблицу</button>
        <br>
      </fieldset>

        <fieldset>
          <legend>Структура таблицы</legend>
          <table>
            <caption>Структура таблицы</caption>
            <tr>
              <th>Имя поля</th>
              <th>Тип данных</th>
            </tr>

            <tr>
              <td>Имя поля 1</td>
              <td>Тип данных 1</td>
            </tr>

            <tr>
              <td>Имя поля 2</td>
              <td>Тип данных 2</td>
            </tr>
            <tr>
              <td>Имя поля 3</td>
              <td>Тип данных 3</td>
            </tr>
            <tr>
              <td>Имя поля 4</td>
              <td>Тип данных 4</td>
            </tr>
          </table>
        </fieldset>


      <fieldset>
        <legend>Добавление поля</legend>
        <label>Введите имя поля: </label>
        <input type="text" name="field_name" placeholder="Имя поля" value=""><br>
        <label>Выберите тип данных поля</label>
        <select>
          <option>INT</option>
          <option>FLOAT</option>
          <option>TIMESTAMP</option>
          <option>VARCHAR</option>
        </select>
        <button name="action" value="add_field">Добавить поле</button>

      </fieldset>
      <fieldset>
        <legend>Удаление поля</legend>
        <select>
          <option>Поле 1</option>
          <option>Поле 2</option>
          <option>Поле 2</option>
          <option>Поле 2</option>
          <option>Поле 2</option>
        </select>
        <!-- <input type="submit" name="delete_field" value="Удалить поле"> -->
        <button name="action" value="delete_field">Удалить</button>
      </fieldset>


    </form>
  </body>
</html>