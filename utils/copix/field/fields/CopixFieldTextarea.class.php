<?php
class CopixFieldTextarea extends CopixAbstractField  implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTMLFieldEdit ()
    {
        return '<textarea name="'.$this->_name.'" '.$this->getParams('extra').'>'.$this->_value.'</textarea>';
    }

    public function getHTMLFieldView ()
    {
        return $this->_value;
    }
}
