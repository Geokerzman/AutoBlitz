<?php
  class Post {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getPosts($page = 1, $postsPerPage = 10) {
      $startFrom = ($page - 1) * $postsPerPage;
  
      $this->db->query('SELECT *,
                          posts.id as postId,
                          users.id as userId,
                          posts.created_at as postCreated,
                          users.created_at as userCreated
                          FROM posts
                          INNER JOIN users
                          ON posts.user_id = users.id
                          ORDER BY posts.created_at DESC
                          LIMIT :start, :count
                      ');
  
      $this->db->bind(':start', $startFrom, PDO::PARAM_INT);
      $this->db->bind(':count', $postsPerPage, PDO::PARAM_INT);
  
      $results = $this->db->resultSet();
  
      return $results;
  }
  
  
  public function getTotalPosts() {
    $this->db->query('SELECT COUNT(*) as totalPosts FROM posts');
    $row = $this->db->single();
    return $row->totalPosts;
}
public function addPost($data) {
  $this->db->query('INSERT INTO posts (title, body, user_id, image) VALUES(:title, :body, :user_id, :image)');
  // Bind values
  $this->db->query('INSERT INTO posts (title, body, user_id, image) VALUES(:title, :body, :user_id, :image)');
  $this->db->bind(':title', $data['title']);
  $this->db->bind(':body', $data['body']);
  $this->db->bind(':user_id', $data['user_id']);
  $this->db->bind(':image', $data['image'], PDO::PARAM_LOB); // Используйте PDO::PARAM_LOB для больших объектов

  // Execute
  if ($this->db->execute()) {
      return true;
  } else {
      return false;
  }
}


    // public function addPost($data){
    //   $this->db->query('INSERT INTO posts (title, user_id, body) VALUES(:title, :user_id, :body)');
    //   // Bind values
    //   $this->db->bind(':title', $data['title']);
    //   $this->db->bind(':user_id', $data['user_id']);
    //   $this->db->bind(':body', $data['body']);

    //   // Execute
    //   if($this->db->execute()){
    //     return true;
    //   } else {
    //     return false;
    //   }
    // }

    public function updatePost($data){
      $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
      // Bind values
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':title', $data['title']);
      $this->db->bind(':body', $data['body']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getPostById($id){
      $this->db->query('SELECT * FROM posts WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
    }

    public function deletePost($id){
      $this->db->query('DELETE FROM posts WHERE id = :id');
      // Bind values
      $this->db->bind(':id', $id);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }