<?php
class CopixFieldPassword extends CopixAbstractField  implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTMLFieldEdit ()
    {
        return '<input type="password" name="'.$this->_name.'" value="'.$this->_value.'" />';
    }

    public function getHTMLFieldView ()
    {
        return '';
    }
}
