<?php

$postsJson = file_get_contents('https://jsonplaceholder.typicode.com/posts');
$postsObj = json_decode($postsJson);

$commentsJson = file_get_contents('https://jsonplaceholder.typicode.com/comments');
$commentsObj = json_decode($commentsJson);

$db = new PDO('sqlite:blog.db');
$db->beginTransaction();

/*
 * Скопировал создание таблиц в отдельный файл, но оставил их и здесь
 * чтобы можно было проверить работоспособность целиком
 */
$db->exec("CREATE TABLE IF NOT EXISTS Posts (
    id INTEGER PRIMARY KEY,
    userId INTEGER, /* потенциальный FOREIGN KEY для таблицы User */
    title TEXT,
    body TEXT)");
$db->exec("CREATE TABLE IF NOT EXISTS Comments (
   id INT NOT NULL PRIMARY KEY,
   postId INT NOT NULL,
   name TEXT NOT NULL,
   email TEXT NOT NULL,
   body TEXT NOT NULL,
   FOREIGN KEY (postId) REFERENCES Posts(id))");

$sql = 'INSERT INTO Posts (id, userId, title, body) VALUES (:id, :userId, :title, :body)';
$stmt = $db->prepare($sql);

foreach ($postsObj as $post) {
    $stmt->execute(['id' => $post->id, 'userId' => $post->userId, 'title' => $post->title, 'body' => $post->body]);
}

$sql = 'INSERT INTO Comments (id, postId, name, email, body) VALUES (:id, :postId, :name, :email, :body)';
$stmt = $db->prepare($sql);

foreach ($commentsObj as $comment) {
    $stmt->execute(['id' => $comment->id, 'postId' => $comment->postId, 'name' => $comment->name, 'email' => $comment->email, 'body' => $comment->body]);
}

$cp = count($postsObj);
$cc = count($commentsObj);

echo "Загружено $cp записей и $cc комментариев";

$db->commit();