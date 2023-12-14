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

      // Добавьте новый метод для фильтрации постов
      public function getFilteredPosts($brand, $model, $year, $page = 1, $postsPerPage = 10) {
          $startFrom = ($page - 1) * $postsPerPage;

          $query = 'SELECT *,
              posts.id as postId,
              users.id as userId,
              posts.created_at as postCreated,
              users.created_at as userCreated
              FROM posts
              INNER JOIN users
              ON posts.user_id = users.id';

          $conditions = [];

          if ($brand !== null) {
              $conditions[] = 'posts.brand = :brand';
          }

          if ($model !== null) {
              $conditions[] = 'posts.model = :model';
          }

          if ($year !== null) {
              $conditions[] = 'posts.year = :year';
          }

          if (!empty($conditions)) {
              $query .= ' WHERE ' . implode(' AND ', $conditions);
          }

          $query .= ' ORDER BY posts.created_at DESC
                LIMIT :start, :count';

          $this->db->query($query);
          $this->db->bind(':brand', $brand);
          $this->db->bind(':model', $model);
          $this->db->bind(':year', $year);
          $this->db->bind(':start', $startFrom, PDO::PARAM_INT);
          $this->db->bind(':count', $postsPerPage, PDO::PARAM_INT);

          $results = $this->db->resultSet();

          return $results;
      }


      public function getTotalFilteredPosts($brand, $model, $year) {
          $query = 'SELECT COUNT(*) as totalPosts FROM posts';

          $conditions = [];

          if ($brand !== null) {
              $conditions[] = 'posts.brand = :brand';
          }

          if ($model !== null) {
              $conditions[] = 'posts.model = :model';
          }

          if ($year !== null) {
              $conditions[] = 'posts.year = :year';
          }

          if (!empty($conditions)) {
              $query .= ' WHERE ' . implode(' AND ', $conditions);
          }

          $this->db->query($query);
          $this->db->bind(':brand', $brand);
          $this->db->bind(':model', $model);
          $this->db->bind(':year', $year);

          $result = $this->db->single();
          return $result->totalPosts;
      }

      public function getTotalPosts() {
    $this->db->query('SELECT COUNT(*) as totalPosts FROM posts');
    $row = $this->db->single();
    return $row->totalPosts;
}

public function getBrands() {
        $this->db->query('SELECT DISTINCT brand FROM auto');
        $brands = $this->db->resultSet();

        return array_column($brands, 'brand');
    }
    
    public function getModels() {
      $this->db->query('SELECT DISTINCT model FROM auto');
      $models = $this->db->resultSet();
  
      $modelArray = [];
      foreach ($models as $model) {
          // Предположим, что значения в колонке model разделены запятой
          $modelValues = explode(',', $model->model);
          $modelArray = array_merge($modelArray, $modelValues);
      }
  
      // Удаляем дубликаты и сортируем
      $modelArray = array_unique($modelArray);
      sort($modelArray);
  
      return $modelArray;
  }
  

    public function getYears() {
        $this->db->query('SELECT DISTINCT year FROM auto');
        $years = $this->db->resultSet();

        return array_column($years, 'year');
    }
    public function getImages($user_id) {
      $this->db->query('SELECT image_path FROM posts WHERE user_id = :user_id');
      $this->db->bind(':user_id', $user_id);

      $results = $this->db->resultSet();

      return $results;
  }

    public function addPost($data) {
      $this->db->query('INSERT INTO posts (title, user_id, brand, model, description, year, image_path) VALUES(:title, :user_id, :brand, :model, :description, :year, :image_path)');  
      $this->db->bind(':title', $data['title']);
      // $this->db->bind(':body', $data['body']);
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':brand', $data['brand']);
      $this->db->bind(':model', $data['model']);
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':year', $data['year']);
      $this->db->bind(':image_path', $data['image_path']); // Add the image path
      // $this->db->bind(':image', $data['image'], PDO::PARAM_LOB); 
  
      // echo "Last SQL query: " . $this->db->getLastQuery();
  
      if ($this->db->execute()) {
          return true;
      } else {
          // Logging
          echo "Error executing query: " . implode(", ", $this->db->errorInfo());
          return false;
      }
  }
    
  

// Old addPost Configs
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