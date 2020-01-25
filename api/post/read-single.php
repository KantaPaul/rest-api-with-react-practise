<?php 

    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../Models/Post.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instantiate blog post object
    $post = new Post($db);

    // Get id
    $post->id = isset($_GET['id']) ? $_GET['id'] : die();

    // get post
    $post->read_single();

    // create array
    $date = new DateTime($post->created_at);
    $post_array = array(
        'id' => $post->id,
        'title' => $post->title,
        'body' => $post->body,
        'author' => $post->author,
        'category_id' => $post->category_id,
        'category_name' => $post->category_name,
        'status' => $post->status,
        'created_at' => date_format($date, 'F j, Y'),
    );

    // Make json
    print_r(json_encode($post_array));