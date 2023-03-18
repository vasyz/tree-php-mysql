<?php


// Подключение к БД


class DB
{

    public $pdo;

    function __construct()
    {
        try {
            require_once('db_config.php');
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
        } catch (PDOException $e) {
            echo "Ошибка подключения к БД: " . $e->getMessage();
            exit();
        }
    }


    function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS nodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parent_id INT DEFAULT NULL,
    title VARCHAR(255),
    FOREIGN KEY(parent_id) REFERENCES nodes(id)
      );";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        echo "CreateTable";
    }

    function deleteNode($node_id) {

        $this->deleteNodeAndChildren($node_id);

    }
    private function deleteNodeAndChildren($id) {
        $stmt = $this->pdo->prepare("SELECT id FROM nodes WHERE parent_id=:id");
        $stmt->execute(['id' => $id]);
        while ($row = $stmt->fetch()) {
            $this->deleteNodeAndChildren($row['id']);
        }
        $stmt = $this->pdo->prepare("DELETE FROM nodes WHERE id=:id");
        $stmt->execute(['id' => $id]);
    }

    function createNode($parent_id = null,  $title = "без назви" )
    {
        $sql = "INSERT INTO nodes (parent_id, title) VALUES (:parent_id, :title)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(['parent_id' => $parent_id, 'title' => $title]);

    }

    function getAllNodes($parent_id = null)
    {
        if(!$parent_id)
        {
            $sql = "SELECT * FROM nodes WHERE parent_id IS NULL;";
            $stmt = $this->pdo->query($sql);
        }
        else
        {
            $sql = "SELECT * FROM nodes WHERE parent_id=:parent_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['parent_id' => $parent_id]);
        }

        $nodes = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $title = $row['title'];
            $childNodes = $this->getAllNodes($id); // Виклик рекурсивної функції для отримання дочірніх вузлів
            $node = array('id' => $id, 'title' => $title, 'childNodes' => $childNodes);
            array_push($nodes, $node);
        }

        return $nodes;

    }



}




