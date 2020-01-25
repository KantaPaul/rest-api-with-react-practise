<?php 

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../Models/Post.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $post = new Post($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Set the id to update
    $post->id = $data->id;
    
    if ($data != '') {
        $post->title = $data->title;
        $post->body = $data->body;
        $post->author = $data->author;
        $post->status = $data->status;
        $post->category_id = $data->category_id;

        // update post
        if ($post->update()) {
            echo json_encode(
                array('message' => 'Post Updates')
            );
        } else {
            echo json_encode(
                array('message' => 'Post Not Updates')
            );
        }
    }
