<?php
class CopixFieldHidden extends CopixAbstractField  implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTML ()
    {
        return $this->getHTMLError();
    }

    public function getHTMLFieldEdit ()
    {
        return null;
    }

    public function fillFromRequest ()
    {
        $this->_value = $this->_defaultValue;
    }
}
