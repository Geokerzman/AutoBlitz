<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Register user
    public function register($data)
    {
        // Data validation
        if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
            // Hashing the pass
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            // Setting user_group 1
            $data['user_group'] = 1;

            // DB query
            $this->db->query('INSERT INTO users (name, email, password, user_group) VALUES(:name, :email, :password, :user_group)');

            // Binding the values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':user_group', $data['user_group']);


            if ($this->db->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            // Загрузка представления с ошибками
            $this->view('users/register', $data);
        }
    }

    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if ($row) {
            $hashed_password = $row->password;
            if (password_verify($password, $hashed_password)) {
                // If pass did match , return the user
                return $row;
            }
        }

        return false;
    }

    // Assigning the default user_group
    public function assignUserGroup($userId, $assignedGroup)
    {
        $this->db->query('UPDATE users SET user_group = :assignedGroup WHERE id = :userId');
        $this->db->bind(':assignedGroup', $assignedGroup);
        $this->db->bind(':userId', $userId);

        return $this->db->execute();
    }


    // Looking for a user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        return $row ? true : false;
    }

    // Looking for a user by ID

    public function getUserById($id)
    {
        $this->db->query('SELECT id, name FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

}