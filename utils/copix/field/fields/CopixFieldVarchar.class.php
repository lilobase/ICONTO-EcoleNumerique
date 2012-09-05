<?php
class CopixFieldVarchar extends CopixAbstractField  implements ICopixFieldForm, ICopixFieldList, ICopixField
{
    public function getHTMLFieldEdit ()
    {
        return _tag('inputtext',array_merge ($this->_params, array('name'=>$this->_name,'value'=>$this->_value)));
    }

    public function getHTMLFieldView ()
    {
        return $this->_value;
    }
}
