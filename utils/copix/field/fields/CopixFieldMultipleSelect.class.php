<?php
class CopixFieldMultipleSelect extends CopixAbstractField implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTMLFieldEdit ()
    {
        return _tag ('multipleselect', array_merge ($this->_params, array ('name'=>$this->_name, 'selected'=>$this->_value)));
    }

    public function fillFromRequest ()
    {
        if ($this->_name != null && is_string ($this->_name)) {
            $this->_value = _request ($this->_name, array ());
        }
    }

}
