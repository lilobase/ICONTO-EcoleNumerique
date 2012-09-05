<?php
/**
 * @package		copix
 * @subpackage	utils
 * @author		Favre Brice, Croes Gérald, Jouanneau Laurent
 * @copyright	2001-2007 CopixTeam
 * @link			http://copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

/**
 * Classe pour manipuler des fichiers CSV
 *
 * @package copix
 * @subpackage utils
 */
class CopixCsv
{
    /**
     * Constante définissant un iterateur ave entête
     */
    const HEADED = true;

    /**
     * Constante définissant un iterateur sans entête
     */
    const NUMBERED = false;

    /**
     * Nom du fichier CSV
     */
    private $_filename;

    /**
     * Délimiteur de champs
     */
    private $_delimiter = ',';

    /**
     * Délimiteur de chaine de caractères
     */
    private $_enclosure = '"';


    /**
     * Nombre de lignes du fichier
     */
    private $_nblines ;

    /**
     * Entetes du fichier
     */
    private $_isHeaded ;

    /**
     * Constructeur de classe
     *
     * @param string $pFilename
     * @param string $pDelimiter
     * @param string $pEnclosure
     */
    public function __construct ($pFileName, $pDelimiter=',', $pEnclosure = '"', $pArrayHead = false)
    {
        $this->_filename = $pFileName;
        $this->_delimiter = $pDelimiter;
        $this->_enclosure = $pEnclosure;
    }

    /**
     * Récupération de l'iterateur CSV sur le fichier
     *
     */
    public function getIterator ($pIsHeaded = self::NUMBERED)
    {
        return new CopixCsvIterator($this->_filename, $this->_delimiter, $this->_enclosure, $pIsHeaded);
    }

    /**
     * Fonction d'ajout de ligne à un fichier CSV
     * @param array $arParams
     */
    public function addLine ($arParams)
    {
        $_dirname = dirname($this->_filename);
        // On teste l'existence du répertoire contenant le fichier
        if (Copixfile::createDir($_dirname)) {
            if ($fd = @ fopen ($this->_filename, "a")){
                fputcsv ($fd, $arParams, $this->_delimiter, $this->_enclosure);
                $this->_nblines++;
                fclose ($fd);
                return true;
            }else{
                return false;
            }
        } else {
            throw new CopixException (_i18n ("copix:copix.error.cache.creatingDirectory", array ($_dirname)));
        }
    }

}

/**
 * Classe Iterateur de parcours de fichiers CSV
 */
class CopixCsvIterator extends LimitIterator implements Countable
{
    protected $_data;
    protected $_current;
    protected $_filehandler;
    protected $_counter;
    protected $_delimiter;
    protected $_enclosure;
    protected $_keys = null;
    const ROW_SIZE = 4096;

    protected $_filename = null;


    public function __construct ($pFile, $pDelimiter, $pEnclosure, $pIsHeaded)
    {
        $this->_filename = $pFile;
        $this->_filehandler = fopen ($pFile,'r');
        $this->_delimiter = $pDelimiter;
        $this->_enclosure = $pEnclosure;
        if ($pIsHeaded === CopixCSV::HEADED) {
            $this->_keys = fgetcsv($this->_filehandler, self::ROW_SIZE, $this->_delimiter, $this->_enclosure);
            $this->_current = array_combine ($this->_keys, fgetcsv($this->_filehandler, self::ROW_SIZE, $this->_delimiter, $this->_enclosure));
        } else {
            $this->_current = fgetcsv($this->_filehandler, self::ROW_SIZE, $this->_delimiter, $this->_enclosure);
        }
        $this->_counter = 0;
    }

    public function current ()
    {
        return $this->_current;
    }

    public function key ()
    {
        return $this->_counter;
    }

    public function next ()
    {
        $this->_current = fgetcsv($this->_filehandler, self::ROW_SIZE, $this->_delimiter, $this->_enclosure);
        if ($this->_current !== false) {
            $this->_counter++;
            if ($this->_keys !== null) {
                $this->_current = array_combine ($this->_keys, $this->_current);
            }
        }
        return $this->_current;
    }

    public function rewind ()
    {
        $this->_counter = 0;
        rewind ($this->_filehandler);
        $this->_current = fgetcsv($this->_filehandler, self::ROW_SIZE, $this->_delimiter, $this->_enclosure);
    }

    public function valid ()
    {
        if ( ! $this->current() ) {
            return FALSE;
        }
        return TRUE;
    }

    public function seek ($position)
    {
        if ($position == 0) {
            $this->rewind();
        } else {
            if ($position < $this->_counter) {
                $this->rewind();
            }
            while ($this->next()) {
                if ($this->_counter == $position) {
                    break;
                }
            }
        }
    }

    public function count()
    {
        //@todo pour les fichiers peu volumineux, un return count (file ($this->_filename)); est plus rapide
        $file = fopen ($this->_filename,'r');
        $count = 0;
        while (fgets ($file)){
            $count++;
        }
        fclose ($file);
        return $count;
    }

    public function __destruct ()
    {
        fclose ($this->_filehandler);
    }

}


