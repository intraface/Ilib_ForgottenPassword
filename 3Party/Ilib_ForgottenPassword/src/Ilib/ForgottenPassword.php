<?php
class Ilib_ForgottenPassword
{
    private $db;
    private $new_password;
    private $observers = array();
    private $table;
    private $map;

    public function __construct($connection, $email, $table = 'user', $mapping = array('username' => 'email', 'password' => 'password'))
    {
        $this->db    = $connection;
        $this->db->loadModule('Extended');
        $this->email = $email;
        $this->table = $table;
        $this->map = $mapping;
    }

    public function iForgotMyPassword($email)
    {
        if (!Validate::email($email)) {
            return false;
        }

        $result = $this->db->query("SELECT " . $this->map['username'] . " FROM " . $this->table . " WHERE " . $this->map['username'] . " = " . $this->db->quote($email, 'text'));
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        if ($result->numRows() != 1) {
            return false;
        }

        $this->updatePassword($this->getNewPassword());

        $this->notifyObservers();

        return true;
    }

    public function getNewPassword()
    {
        if (!empty($this->new_password)) {
            return $this->new_password;
        }
        $generator = new Ilib_RandomKeyGenerator(6);
        return ($this->new_password = $generator->generate());
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function updatePassword($password)
    {
        $fields[$this->map['password']] = md5($password);
        $type = MDB2_AUTOQUERY_UPDATE;
        $where = $this->map['username'] . ' = ' . $this->db->quote($this->email, 'text');
        $result = $this->db->autoExecute($this->table, $fields, $type, $where);
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        return true;
    }

    private function notifyObservers()
    {
        foreach ($this->getObservers() as $observer) {
            $observer->update($this);
        }
    }

    public function addObserver($observer)
    {
        $this->observers[] = $observer;
    }

    private function getObservers()
    {
        return $this->observers;
    }
}