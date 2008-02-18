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

        $forgotten = new Ilib_ForgottenPassword($this->registry->get('database'), $this->POST['email']);
        $forgotten->addObserver(new Ilib_ForgottenPassword_Email($this->url('/login')));

        try {
            if (!$forgotten->iForgotMyPassword($this->POST['email'])){
                return $this->render(dirname(__FILE__) . '/../templates/form.tpl.php', array('msg' => 'Det gik ikke godt. E-mailen kunne ikke sendes. Du kan prøve igen senere.'));
            } else {
                return $this->render(dirname(__FILE__) . '/../templates/success.tpl.php', array('msg' => 'Vi har sendt en e-mail til dig med en ny adgangskode, som du bør gå ind og lave om med det samme.'));
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}