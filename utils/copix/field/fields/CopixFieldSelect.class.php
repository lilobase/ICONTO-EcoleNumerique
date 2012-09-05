<?php
class CopixFieldSelect extends CopixAbstractField implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTMLFieldEdit ()
    {
        return _tag ('select', array_merge ($this->_params, array ('name'=>$this->_name, 'selected'=>$this->_value)));;
    }

}
