<?php
class Ilib_ForgottenPassword
{
    private $db;
    private $new_password;
    private $observers = array();
    private $table = 'user';
    private $map = array('email'    => 'email',
                         'password' => 'password');

    function __construct($connection, $email)
    {
        $this->db    = $connection;
        $this->email = $email;
    }

    function setTable($table)
    {
        $this->table = $table;
    }

    function setMapping($mapping)
    {
        $this->map = $mapping;
    }

    function iForgotMyPassword($email)
    {
        if (!Validate::email($email)) {
            return false;
        }

        $result = $this->db->query("SELECT id FROM " . $this->table . " WHERE " . $this->map['email'] . " = " . $this->db->quote($email, 'text'));
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        if ($result->numRows() != 1) {
            return false;
        }
        $row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

        $this->updatePassword($this->getNewPassword());

        $this->notifyObservers();

        return true;
    }


    function getNewPassword()
    {
        if (!empty($this->new_password)) {
            return $this->new_password;
        }
        $generator = new Ilib_RandomKeyGenerator(6);
        return ($this->new_password = $generator->generate());
    }

    function getEmail()
    {
        return $this->email;
    }

    function updatePassword($password)
    {
        $fields[$this->map['password']] = md5($password);
        $type = MDB2_AUTOQUERY_UPDATE;
        $where = 'WHERE  ' . $this->map['email'] . ' = ' . $this->db->quote($this->email, 'string');
        $result = $this->db->autoExecute($this->table, $fields, $type, $where);
        if (PEAR::isError($result)) {
            throw new Exception($result->getUserInfo());
        }
        $this->notifyObservers();
        return true;
    }

    function notifyObservers()
    {
        foreach ($this->getObservers() as $observer) {
            $observer->update($this);
        }
    }

    function addObserver($observer)
    {
        $this->observers[] = $observer;
    }

    function getObservers()
    {
        return $this->observers;
    }
}