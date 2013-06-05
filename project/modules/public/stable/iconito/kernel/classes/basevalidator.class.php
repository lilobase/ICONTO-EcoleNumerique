<?php

/**
 * Classe de base des validator de formulaire
 */
abstract class BaseValidator
{
    /**
     * @var mixed L'objet que l'on doit valider
     */
    protected $object;

    /**
     * @var array Les options de la validation
     */
    protected  $options = array();

    /**
     * @var array Les erreurs de validation
     */
    protected $errors = array();

    public function __construct($object, $options = array())
    {
        $this->setObject($object);

        $this->setOptions($options);
    }

    /**
     * Permet de savoir si le formulaire est valide
     *
     * @return bool
     */
    public function isValid()
    {
        $this->validate();

        return (0 === count($this->getErrors()));
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * Retourne les valeurs passées à la validation
     *
     * @return array
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Défini les options de validation
     *
     * @param array $options Les options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * Retourne toutes les options de validation
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Retourne la valeur d'une option (eventuellement la valeur par défaut si cette clé n'est pas trouvée)
     *
     * @param string $key     La clé d'option dont on veut la valeur
     * @param null   $default La valeur par défaut
     *
     * @return mixed
     */
    public function getOption($key, $default = null)
    {
        if (array_key_exists($key, $this->options)) {
            return $this->options[$key];
        }

        return $default;
    }

    /**
     * Ajoute une erreur dans le tableau interne des erreurs
     *
     * @param string $message Le message de l'erreur
     */
    public function addError($message)
    {
        $this->errors[] = $message;
    }

    /**
     * Retourne les erreurs de validation
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Méthode effectuant la validation et définissant les erreurs (s'il y en a)
     *
     * @return null
     */
    abstract protected function validate();
}
