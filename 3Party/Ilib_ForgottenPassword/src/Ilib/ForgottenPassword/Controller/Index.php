<?php
class Ilib_ForgottenPassword_Controller_Index extends k_Controller
{
    function GET()
    {
        $this->document->title = $this->__('Forgotten password');

        return $this->render(dirname(__FILE__) . '/../templates/form.tpl.php');

    }

    function POST()
    {
        $this->document->title = $this->__('Forgotten password');

        $forgotten = $this->registry->create('forgottenpassword', $this->POST['email']);
        $forgotten->addObserver(new Ilib_ForgottenPassword_Email($this->url('/login')));

        try {
            if (!$forgotten->iForgotMyPassword($this->POST['email'])){
                return $this->render(dirname(__FILE__) . '/../templates/form.tpl.php', array('msg' => $this->__('It went bad. The email could not be sent. Try again later.')));
            } else {
                return $this->render(dirname(__FILE__) . '/../templates/success.tpl.php', array('msg' => $this->__('We have sent you an email with a new password.')));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}