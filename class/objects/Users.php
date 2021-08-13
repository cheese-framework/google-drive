<?php

class Users
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }


    public function setTotalSizeUploaded($size, $id)
    {
        $this->db->query("UPDATE users SET totalSizeUploaded=? WHERE id=?");
        $this->db->bind(1, $size);
        $this->db->bind(2, $id);
        return $this->db->execute();
    }

    public function getTotalSizeUploaded($id)
    {
        $this->db->query("SELECT totalSizeUploaded FROM users WHERE id=?");
        $this->db->bind(1, $id);
        $data = $this->db->single();
        return ($this->db->rowCount() > 0) ? $data->totalSizeUploaded : 0;
    }

    public function getFolder($id)
    {
        $this->db->query("SELECT folder FROM users WHERE id=?");
        $this->db->bind(1, $id);
        $data = $this->db->single();
        if ($this->db->rowCount() > 0) {
            return $data->folder;
        } else {
            return "uploads";
        }
    }

    public function register($username, $email, $password, $folder)
    {
        if (!$this->userExists($email)) {
            $this->db->query("INSERT INTO users (username, email, password, totalSizeUploaded,folder) VALUES(?,?,?,?,?)");
            $this->db->bind(1, $username);
            $this->db->bind(2, $email);
            $this->db->bind(3, $password);
            $this->db->bind(4, 0);
            $this->db->bind(5, $folder);
            return $this->db->execute();
        } else {
            throw new Exception("E-Mail is already in use by another user. <a href='login.php'>Login</a> if its yours");
        }
    }

    private function userExists($email)
    {
        $this->db->query("SELECT email FROM users WHERE email=?");
        $this->db->bind(1, $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function login($data, $password)
    {
        $this->db->query("SELECT * FROM users WHERE username=? OR email=?");
        $this->db->bind(1, $data);
        $this->db->bind(2, $data);
        $result = $this->db->single();
        if ($this->db->rowCount() > 0) {
            if (password_verify($password, $result->password)) {
                return $result;
            } else {
                throw new Exception("Incorrect credentials");
            }
        } else {
            throw new Exception("User not found!<br>Check your details or <a href='register.php'>register</a>");
        }
    }
}