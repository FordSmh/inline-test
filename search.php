<form action="search.php" method="post">
    <label for="query">Поиск по комментариям</label>
    <input type="text" name="query">
    <input type="submit" value="Поиск">
</form>
<?php
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (mb_strlen($_POST['query']) > 2) {
        $query = '%'.htmlspecialchars(trim($_POST['query'])).'%';
        $db = new PDO('sqlite:blog.db');

        $sql = 'SELECT title, c.body FROM Posts p INNER JOIN Comments c ON (p.id = c.postId) WHERE c.body LIKE (:query)';
        $stmt = $db->prepare($sql);
        $stmt->execute(['query' => $query]);

        $result = $stmt->fetchAll();

        echo 'Найдено комментариев - '.count($result).'<br><hr>';
        foreach ($result as $row) {
            echo 'Пост - <b>'.$row['title'].'</b><br>';
            echo 'Комментарий - <b>'.$row['body'].'</b><br><hr>';
        }
    }
    else {
        echo 'Введите запрос длиннее';
    };
}
?>