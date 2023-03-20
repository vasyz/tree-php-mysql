<?php

class DB
{

    /**
     * @var PDO
     */
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


    /**
     * @function initDB create table
     */
    public function initDB()
    {
        $sql = "CREATE TABLE IF NOT EXISTS nodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    parent_id INT DEFAULT NULL,
    title VARCHAR(255),
    FOREIGN KEY(parent_id) REFERENCES nodes(id)
      );";
        try {

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        } catch (PDOException $e) {

            return ["error" => $e->getMessage()];

        } catch (Exception $e) {

            return ["error" => $e->getMessage()];
        }
    }

    /**
     * @param $node_id
     */
    public function deleteNode($node_id)
    {

        $this->deleteNodeAndChildren($node_id);

    }

    /**
     * @param $id
     */
    private function deleteNodeAndChildren($id)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT id FROM nodes WHERE parent_id=:id");

            $stmt->execute(['id' => $id]);

            while ($row = $stmt->fetch()) {

                $this->deleteNodeAndChildren($row['id']);

            }
            $stmt = $this->pdo->prepare("DELETE FROM nodes WHERE id=:id");

            $stmt->execute(['id' => $id]);

            return true;

        } catch (PDOException $e) {

            return ["error" => $e->getMessage()];

        } catch (Exception $e) {

            return ["error" => $e->getMessage()];
        }
    }

    /**
     * @param null $parent_id
     * @param string $title
     */
    public function createNode($parent_id = null, $title = "")
    {
        $sql = "INSERT INTO nodes (parent_id, title) VALUES (:parent_id, :title)";

        try {

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute(['parent_id' => $parent_id, 'title' => $title]);

            return true;

        } catch (PDOException $e) {

            return ["error" => $e->getMessage()];

        } catch (Exception $e) {

            return ["error" => $e->getMessage()];

        }
    }


    /**
     * @param null $parent_id
     * @return array
     */
    function getAllNodes($parent_id = null)
    {
        try {

            if (!$parent_id) {

                $sql = "SELECT * FROM nodes WHERE parent_id IS NULL;";

                $stmt = $this->pdo->query($sql);

            } else {

                $sql = "SELECT * FROM nodes WHERE parent_id=:parent_id";

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute(['parent_id' => $parent_id]);
            }

            $nodes = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $id = $row['id'];

                $title = $row['title'];

                $childNodes = $this->getAllNodes($id);

                $node = array('id' => $id, 'title' => $title, 'childNodes' => $childNodes);

                array_push($nodes, $node);

            }

            return $nodes;

        } catch (PDOException $e) {

            return ["error" => $e->getMessage()];

        } catch (Exception $e) {

            return ["error" => $e->getMessage()];

        }

    }


}




