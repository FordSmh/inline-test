CREATE TABLE Posts (
    id INT NOT NULL PRIMARY KEY,
    userId INT NOT NULL, /* потенциальный FOREIGN KEY для таблицы User */
    title TEXT,
    body TEXT,
)

CREATE TABLE Comments (
   id INT NOT NULL PRIMARY KEY,
   postId INT NOT NULL,
   name TEXT NOT NULL,
   email TEXT NOT NULL,
   body TEXT NOT NULL,
   FOREIGN KEY (postId) REFERENCES Posts(id)
)