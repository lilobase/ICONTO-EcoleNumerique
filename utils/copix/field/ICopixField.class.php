<?php
interface ICopixField
{
    public function __construct ($pParams);

    public function getHTML ();

    public function getHTMLField ();

    public function getHTMLError ($pTemplate = 'copix:templates/validator.error.tpl');

    public function fillFromRequest ();

    public function reset ();

    public function setDefaultValue ($pDefault);

    public function getName ();

    public function getValue ();

    public function getField ();


}
