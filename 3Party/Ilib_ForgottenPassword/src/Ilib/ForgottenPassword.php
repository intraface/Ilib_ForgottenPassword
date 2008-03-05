<?php
class Ilib_ForgottenPassword
{
    private $db;
    private $new_password;
    private $observers = array();
    private $table;
    private $map;
    private $email;

    public function __construct($connection, $table = 'user', $mapping = array('username' => 'email', 'password' => 'password'))
    {
        $this->db    = $connection;
        $this->db->loadModule('Extended');
        $this->table = $table;
        $this->map   = $mapping;
    }

    function getEmail()
    {
        return $this->email;
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

        $this->updatePassword($email, $this->getNewPassword());

        $this->notifyObservers();

        return true;
    }

    function getRandomKeyGenerator()
    {
        return new Ilib_RandomKeyGenerator(6);
    }

    public function getNewPassword()
    {
        if (!empty($this->new_password)) {
            return $this->new_password;
        }
        $generator = $this->getRandomKeyGenerator();
        return ($this->new_password = $generator->generate());
    }

    public function updatePassword($email, $password)
    {
        $this->email = $email;
        $fields[$this->map['password']] = md5($password);
        $type = MDB2_AUTOQUERY_UPDATE;
        $where = $this->map['username'] . ' = ' . $this->db->quote($email, 'text');
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