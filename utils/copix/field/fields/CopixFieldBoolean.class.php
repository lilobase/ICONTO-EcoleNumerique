<?php
class CopixFieldBoolean extends CopixAbstractField implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTMLFieldEdit ()
    {
        return '<input type="checkbox" value="OUI" name="'.$this->getName ().'" '.(($this->getValue () === 'OUI') ? 'checked' : null ).'"/>';
    }

    public function fillFromRequest ()
    {
        $this->_value = _request ($this->_name, 'NON');
    }
}
