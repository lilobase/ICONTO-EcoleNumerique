<?php

abstract class CopixAbstractCompositeField extends CopixAbstractField implements ICopixField
{
    protected $_tabField     = array ();

    public function getTabField ()
    {
        return $this->_tabField;
    }

    public function getCompositeField ($pKey)
    {
        if (!isset ($this->_tabField[$pKey])) {
            $this->addField($pKey);
        }
        return $this->_tabField[$pKey];

    }

    public function fillFromRequest ()
    {
        parent::fillFromRequest();
        foreach ($this->_tabField as $field) {
            $field->fillFromRequest ();
        }
    }

    public function addField ($pName, $pType = null, $pParams = array ())
    {
        $this->_tabField[$pName] = CopixFieldFactory::get($pType,array_merge ($pParams, array ('name'=>$pName)));
    }

    /*public function getHTML () {
        $html = '';
        foreach ($this->_tabField as $field) {
            $html .= $field->getHTML ();
        }
        return $html;
    }*/

    public function getHTMLField ()
    {
        $html = '';
        foreach ($this->_tabField as $field) {
            $html .= $field->getHTMLField ();
        }
        return $html;
    }

    public function makeCondition ($pDatasource)
    {
        parent::makeCondition($pDatasource);
        foreach ($this->_tabField as $field) {
                $field->makeCondition ($pDatasource);
        }
    }

    public function fillRecord ($pRecord)
    {
        parent::fillRecord($pRecord);
        foreach ($this->_tabField as $field) {
            $field->fillRecord ($pRecord);
        }
    }

    public function fillFromRecord ($pRecord)
    {
        parent::fillFromRecord($pRecord);
        foreach ($this->_tabField as $field) {
            $field->fillFromRecord ($pRecord);
        }
    }

    public function valid ()
    {
        foreach ($this->_tabField as $field) {
            $field->valid ();
        }
    }

    public function addValidator ($pValidator, $pParams = array (), $pMessage = null)
    {
        if (!is_array ($pValidator)) {
            throw new CopixException ('doit être un tableau');
        }
        foreach ($pValidator as $key=>$validator) {
            if (isset ($this->tabField[$key])) {
                $this->tabField[$key]->addValidator ($validator);
            }
        }
    }

}

