<?php 

class Post {
    // DB Staff
    private $conn;
    private $table = 'posts';

    // Post properties
    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    // contructor with db
    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = 'SELECT  
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
                FROM
                    '. $this->table .' p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                ORDER BY
                    p.created_at DESC';
        
        // prepare Statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // Get single post
    public function read_single() {
        $query = 'SELECT  
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
                FROM
                    '. $this->table .' p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                WHERE
                    p.id = ?
                LIMIT 0,1    
                ';
        
        // prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind Id
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set propertis
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
        $this->created_at = $row['created_at'];
    }

    // Create post
    public function create() {
//        $query = 'INSERT INTO ' . $this->table . '
//        SET
//            title = :title,
//            body = :body,
//            author = :author,
//            category_id = :category_id,
//            created_at = :created_at ,
//        ';
//
//        // Prepare statement
//        $stmt = $this->conn->prepare($query);
//
//        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

//
//        // Binda data
//        $stmt->bindParam(':title', $this->title);
//        $stmt->bindParam(':body', $this->body);
//        $stmt->bindParam(':author', $this->author);
//        $stmt->bindParam(':category_id', $this->category_id);
//        $stmt->bindParam(':created_at', $this->created_at);
//
//        // Execute Query
//        if ($stmt->execute()) {
//            return true;
//        }

        $data = [
            'title' => $this->title,
            'body' => $this->body,
            'author' => $this->author,
            'category_id'=> $this->category_id,
        ];
        $sql = "INSERT INTO $this->table (title, body, author, category_id) VALUES (:title, :body, :author, :category_id)";
        $stmt= $this->conn->prepare($sql);
        if($stmt->execute($data)){
            return true;
        }


        // Print error if something goes wrong
        printf("Error : %s. \n", $stmt->error);

        return false;
    }
}