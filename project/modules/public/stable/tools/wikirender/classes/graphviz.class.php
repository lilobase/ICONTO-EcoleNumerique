<?php
/**
* @package		tools
* @subpackage	wikirender
* @author	Patrice Ferlet
* @copyright 2001-2006 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* @package		tools
 * @subpackage	wikirender
 */
class GraphViz
{
    public $code = "";
    public $mode = "dot";
    public $hash;

    /**
     * Constructeur
     * @param	string	$pCode	le code wiki du graphe
     * @param	string 	$pMode (dot ou neato) le mode de rendu désiré
     */
    public function __construct ($pCode, $pMode="dot")
    {
        switch ($mode){
            case "neato":
                $this->mode = "neato";
                break;
            default:
                $this->mode = "dot";
        }
        $this->code = $code;
    }

    /**
     * Rendu HTML de l'élément
     *
     * @return unknown
     */
    public function render ()
    {
        $path = COPIX_CACHE_PATH."/graphviz/";
        CopixFile::createDir ($path);

        $md5 = md5 (stripslashes ($this->code));
        $this->hash=$md5;

        $file = $md5.".png";
        if (!file_exists($path.$file)){
            $this->_render ($file);
        }
        return $file;
    }

    public function _render($file)
    {
        $unik = uniqid("hash");
        $dot = $unik.".dot";
        chdir(COPIX_CACHE_PATH."/graphviz/");
        $thunk = $this->wrap();

        $fp = fopen("$dot","w+");
        fputs($fp,$thunk);
        fclose($fp);

        $command=$this->mode." -Tpng ".$dot." -o $file";
        exec($command);
        //generate map
        $command=$this->mode." -Tismap ".$dot." -o ".$this->hash.".map";
        exec($command);
        /*
        //Anti alias tests
        $command=$this->mode." -Tps ".$dot." -o $file.ps";
        exec($command);
        $command = "gs -q -dNOPAUSE -dBATCH -dTextAlphaBits=4 -dGraphicsAlphaBits=4 -sDEVICE=png16m -sOutputFile=$file $file.ps";
        exec($command);
        unlink ($file.".ps");
        */
        sleep(3);
        @unlink($dot);
    }

    public function getMap()
    {
        $current = getcwd ();
        chdir (COPIX_CACHE_PATH."/graphviz/");
        $this->map = file($this->hash.".map");

        $toReturn ="<MAP NAME=\"".$this->hash."\">\n";
        foreach($this->map as $mapelem){
            //rectangle (56,6) (128,54) http:///www.google.fr A
            //rectangle (56,102) (128,150) http://www.metal3d.org B
            $mapelem = str_replace("(","",$mapelem);
            $mapelem = str_replace(")","",$mapelem);
            $elems = explode(" ",$mapelem);
            $shape = "rect";
            $href = $elems[3];
            $coords= $elems[1].",".$elems[2];
            $toReturn .= "\t".'<AREA shape="'.$shape.'" HREF="'.$href.'" COORDS="'.$coords.'" >'."\n";
        }
        $toReturn.="</MAP>";
        chdir ($current);
        return $toReturn;
    }

    public function getMapName()
    {
        return $this->hash;
    }

    public function wrap()
    {
        if($this->mode=="neato"){
            return <<< EOF
graph G{
$this->code
}
EOF;

        }
        return <<< EOF
digraph G{
$this->code
}
EOF;
    }
}
