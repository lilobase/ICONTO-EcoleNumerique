<?php
abstract class CopixAbstractField implements ICopixField
{
    protected $_params       = array ();

    protected $_name          = null;

    protected $_field        = null;

    protected $_defaultValue = null;

    protected $_value        = null;

    protected $_validator    = null;

    protected $_errors       = null;

    protected $_label		 = null;

    protected $mode = null;

    public function merge ($pField)
    {
        $this->_errors = $pField->getErrors ();
        $this->_value = $pField->getValue ();
    }

    public function getErrors ()
    {
        return $this->_errors;
    }

    public function getName ()
    {
        return $this->_name;
    }

    public function getValue ()
    {
        return $this->_value;
    }

    public function getField ()
    {
        return $this->_field;
    }

    public function getParams ($pParam, $pDefault = null)
    {
        return isset ($this->_params[$pParam]) ? $this->_params[$pParam] : $pDefault;
    }

    public function setParam ($pParam, $pValue)
    {
        $this->_params[$pParam] = $pValue;
    }

    public function setParams ($pParams)
    {
        $this->_params = $pParams;
    }

    public function setDefaultValue ($pDefault)
    {
        $this->_defaultValue = $pDefault;
    }

    public function __construct ($pParams)
    {
        if (isset ($pParams['validator'])) unset ($pParams['validator']);
        $this->_params       = $pParams;
        $this->_name         = $this->getParams('name');
        $this->_field        = $this->getParams('field', $this->_name);
        $this->_defaultValue = $this->getParams('value');
        $this->_value        = $this->getParams('value');
        $this->_label        = $this->getParams('label', $this->_name);
        //$this->_validator    = new CopixCompositeValidator();
    }

    public function getLabel ()
    {
        return $this->_label;
    }

    public function setMode (&$pMode)
    {
        $this->mode = &$pMode;
    }

    public function getMode ()
    {
        return (isset ($this->mode) ? $this->mode : 'edit');
    }

    public function fillFromRequest ()
    {
        if ($this->_name != null && is_string ($this->_name)) {
            $this->_value = _request ($this->_name, $this->_defaultValue);
        }
    }

    public function fillRequest ($pPrefix)
    {
        CopixRequest::set($pPrefix.$this->_field, $this->_value);
    }

    public function reset ()
    {
        $this->_value = $this->_defaultValue;
    }

    public function makeCondition ($pDatasource)
    {
        if ($this->_value != null) {
            $pDatasource->addCondition ($this->_field, $this->getParams('condition', 'like'), $this->getParams('beforeValue', '').$this->_value.$this->getParams('afterValue', ''));
        }
    }

    public function valid ()
    {
        //$this->_errors = $this->_validator->isValid ($this->_value);
        //$this->_cleanValidator ();
        //return $this->_errors;
    }

    public function fillRecord ($pRecord)
    {
        $pRecord->{$this->_field} = $this->_value;
    }

    public function fillFromRecord ($pRecord)
    {
        $this->_value = isset ($pRecord->{$this->_field}) ? $pRecord->{$this->_field} : $this->_defaultValue;
    }

    public function _cleanValidator ()
    {
        $this->_validator    = new CopixCompositeValidator();
    }

    public function addValidator ($pValidator, $pParams = array (), $pMessage = null)
    {
        if (!is_array ($pValidator)) {
                $pValidator = array ($pValidator);
        }
        foreach ($pValidator as $validator) {
            $this->_validator->addValidator ($validator, $pParams, $pMessage);
        }
    }

    public function getHTMLError ($pTemplate = 'copix:templates/validator.error.tpl')
    {
        /*if ($this->getMode() === 'edit') {
            return ($this->_errors != null && $this->_errors->haveErrors ()) ? $this->_errors->getHTML ($pTemplate) : '';;
        }*/
        if (is_array ($this->_errors)) {
            $toReturn = implode ('<br />',$this->_errors);
            $this->_errors = array ();
            return $toReturn;
        }
        return null;
    }

    public function addError ($pError)
    {
        $this->_errors = (is_array($pError)) ? $pError : array ($pError);
    }

    public function getHTML ($pMode = null)
    {
        if ($pMode === null) {
            return $this->getHTMLField ().$this->getHTMLError();
        } elseif ($pMode === 'view') {
            return $this->getHTMLFieldView ().$this->getHTMLError();
        }

    }

    public function getHTMLField ()
    {
        if ($this->getMode() === 'edit') {
            return $this->getHTMLFieldEdit ();
        } else {
            return $this->getHTMLFieldView();
        }
    }

    public function getHTMLFieldView ()
    {
        return $this->getHTMLFieldEdit ();
    }

}
