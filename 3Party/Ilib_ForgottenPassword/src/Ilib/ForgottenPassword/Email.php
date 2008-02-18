<?php
class Ilib_ForgottenPassword_Email
{
    private $url;

    function __construct($url)
    {
        $this->url = $url;
    }

    function update($forgotten)
    {
        $subject = 'Tsk, glemt din adgangskode?';

        $body   = "Huha, det var heldigt, at vi stod på spring i kulissen, så vi kan hjælpe dig med at lave en ny adgangskode.\n\n";
        $body .= "Din nye adgangskode er: " . $forgotten->getNewPassword() . "\n\n";
        $body .= "Du kan logge ind fra:\n\n";
        $body .= "<".$this->url.">\n\n";
        $body .= "Med venlig hilsen\nDin hengivne webserver";

        if (mail($forgotten->getEmail(), $subject, $body, "From: Intraface.dk <robot@intraface.dk>\nReturn-Path: robot@intraface.dk")) {
            return true;
        } else {
            return false;
        }
    }
}