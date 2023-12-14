<?php
  class User {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Regsiter user
    public function register($data){
      // Валидация данных
      if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Хеширование пароля
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // Присвоение user_group 1
          $data['user_group'] = 1;

          // Вставка данных в базу данных
          $this->db->query('INSERT INTO users (name, email, password, user_group) VALUES(:name, :email, :password, :user_group)');

          // Привязка значений
          $this->db->bind(':name', $data['name']);
          $this->db->bind(':email', $data['email']);
          $this->db->bind(':password', $data['password']);
          $this->db->bind(':user_group', $data['user_group']);

          // Выполнение запроса
          if($this->db->execute()){
              return true;
          } else {
              return false;
          }
      } else {
          // Загрузка представления с ошибками
          $this->view('users/register', $data);
      }
  }

  public function login($email, $password) {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    if ($row) {
        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            // Пароль совпал, возвращаем пользователя
            return $row;
        }
    }

    return false;
}

  // Метод для присвоения user_group
  public function assignUserGroup($userId, $assignedGroup) {
    $this->db->query('UPDATE users SET user_group = :assignedGroup WHERE id = :userId');
    $this->db->bind(':assignedGroup', $assignedGroup);
    $this->db->bind(':userId', $userId);

    return $this->db->execute();
}


  // Поиск пользователя по email
  public function findUserByEmail($email){
      $this->db->query('SELECT * FROM users WHERE email = :email');
      $this->db->bind(':email', $email);

      $row = $this->db->single();

      return $row ? true : false;
  }

  // Получение пользователя по ID
  public function getUserById($id){
      $this->db->query('SELECT id, name FROM users WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();

      return $row;
  }

  }