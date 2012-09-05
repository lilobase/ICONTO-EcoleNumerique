<?php
/**
* Éléments de configuration pour WikiRenderer 2.0
* This library comes originaly from the WikiRenderer Library made by Laurent Jouanneau,
* and was integrated by himself into Copix.
*
* @package   copix
* @subpackage generaltools
* @version   $Id: CopixWikiRenderer.conf.php,v 1.20 2008-11-25 11:07:18 cbeyer Exp $
* @author   Jouanneau Laurent
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_MODULE_PATH.'malle/'.COPIX_CLASSES_DIR.'malleservice.class.php');
require_once (COPIX_MODULE_PATH.'malle/'.COPIX_CLASSES_DIR.'kernelmalle.class.php');
require_once (COPIX_UTILS_PATH.'WikiIconito.lib.php');

class CopixWikiRendererConfig
{
    /**
    * @var array   liste des tags inline
    */
    public $inlinetags= array(
    'multimedia'   =>array('[[',']]', array('src','type'),'iconito_multimedia'),
    'strong' =>array('__','__',      null,null),
    'em'     =>array('\'\'','\'\'',  null,null),
    'code'   =>array('@@','@@',      null,null),
    'q'      =>array('^^','^^',      array('lang','cite'),null),
    'cite'   =>array('{{','}}',      array('title'),null),
    'acronym'=>array('??','??',      array('title'),null),
    'link'   =>array('[',']',        array('href','hreflang','title','target'),'wikibuildlink'),
    'image'  =>array('((','))',      array('src','alt','align','longdesc'),'wikibuildimage'),
    'anchor' =>array('~~','~~',      array('name'),'wikibuildanchor')
    );


    /**
    * liste des balises de type bloc autorisées.
    * Attention, ordre important (p en dernier, car c'est le bloc par defaut..)
    */
    public $bloctags = array('title'=>true, 'list'=>true,
    'pre'=>true,'hr'=>true, 'blockquote'=>true,'definition'=>true,'table'=>true, 'p'=>true);


    public $simpletags = array('%%%'=>'<br />', ':-)'=>'<img src="laugh.png" alt=":-)" />');

    /**
    * @var   integer   niveau minimum pour les balises titres
    */
    public $minHeaderLevel=3;

    /**
    * indique le sens dans lequel il faut interpreter le nombre de signe de titre
    * true -> ! = titre , !! = sous titre, !!! = sous-sous-titre
    * false-> !!! = titre , !! = sous titre, ! = sous-sous-titre
    */
    public $headerOrder=false;
    public $escapeSpecialChars=true;
    public $inlineTagSeparator='|';
    public $blocAttributeTag='°°';

    public $checkWikiWord = false;
    public $checkWikiWordFunction = null;

}

// ===================================== fonctions de générateur de code HTML spécifiques à certaines balises inlines

function wikibuildlink($contents, $attr)
{
    $cnt=count($contents);
    $attribut='';

    if($cnt >1){
        if($cnt> count($attr))
        $cnt=count($attr)+1;
        if(strpos($contents[1],'javascript:')!==false) // for security reason
        $contents[1]='#';

        for($i=1;$i<$cnt;$i++){
            $attribut.=' '.$attr[$i-1].'="'.$contents[$i].'"';
        }
    }else{
        if(strpos($contents[0],'javascript:')!==false) // for security reason
        $contents[0]='#';
        $attribut=' href="'.$contents[0].'"';
        if(strlen($contents[0]) > 40)
        $contents[0]=substr($contents[0],0,40).'(..)';
    }
    return '<a'.$attribut.'>'.$contents[0].'</a>';

}

function wikibuildanchor($contents, $attr)
{
    return '<a name="'.$contents[0].'"></a>';
}

function wikibuilddummie($contents, $attr)
{
    return (isset($contents[0])?$contents[0]:'');
}

function wikibuildimage($contents, $attr)
{
    $cnt=count($contents);
    $attribut='';
    if($cnt > 4) $cnt=4;
    switch($cnt){
        case 4:
        $attribut.=' longdesc="'.$contents[3].'"';
        case 3:
        if($contents[2]=='l' ||$contents[2]=='L' || $contents[2]=='g' || $contents[2]=='G')
        $attribut.=' style="float:left;"';
        elseif($contents[2]=='r' ||$contents[2]=='R' || $contents[2]=='d' || $contents[2]=='D')
        $attribut.=' style="float:right;"';
        case 2:
        $attribut.=' alt="'.$contents[1].'"';
        case 1:
        default:
        $attribut.=' src="'.$contents[0].'"';
        if($cnt == 1) $attribut.=' alt=""';
    }
    return '<img'.$attribut.' />';

}



// ===================================== déclaration des différents bloc wiki

/**
* traite les signes de types liste
*/
class WRB_list extends CopixWikiRendererBloc
{
    public $_previousTag;
    public $_firstItem;
    public $_firstTagLen;
    public $type='list';
    public $regexp="/^([\*#-]+)(.*)/";

    public function open()
    {
        $this->_previousTag = $this->_detectMatch[1];
        $this->_firstTagLen = strlen($this->_previousTag);
        $this->_firstItem=true;

        if(substr($this->_previousTag,-1,1) == '#')
        return "<ol>\n";
        else
        return "<ul>\n";
    }
    public function close()
    {
        $t=$this->_previousTag;
        $str='';

        for($i=strlen($t); $i >= $this->_firstTagLen; $i--){
            $str.=($t{$i-1}== '#'?"</li></ol>\n":"</li></ul>\n");
        }
        return $str;
    }

    public function getRenderedLine()
    {
        $d=strlen($this->_previousTag) - strlen($this->_detectMatch[1]);
        $str='';

        if( $d > 0 ){ // on remonte d'un cran dans la hierarchie...
        $str=(substr($this->_previousTag, -1, 1) == '#'?"</li></ol>\n</li>\n<li>":"</li></ul>\n</li>\n<li>");
        $this->_previousTag=substr($this->_previousTag,0,-1); // pour être sur...

        }elseif( $d < 0 ){ // un niveau de plus
        $c=substr($this->_detectMatch[1],-1,1);
        $this->_previousTag.=$c;
        $str=($c == '#'?"<ol>\n<li>":"<ul>\n<li>");

        }else{
            $str=($this->_firstItem ? '<li>':'</li><li>');
        }
        $this->_firstItem=false;
        return $str.$this->_renderInlineTag($this->_detectMatch[2]);

    }


}


/**
* traite les signes de types table
*/
class WRB_table extends CopixWikiRendererBloc
{
    public $type='table';
    public $regexp="/^\| ?(.*)/";
    public $_openTag='<table border="1">';
    public $_closeTag='</table>';

    public $_colcount=0;

    public function open()
    {
        $this->_colcount=0;
        return $this->_openTag;
    }


    public function getRenderedLine()
    {
        $result=explode(' | ',trim($this->_detectMatch[1]));
        $str='';
        $t='';

        if((count($result) != $this->_colcount) && ($this->_colcount!=0))
        $t='</table><table border="1">';
        $this->_colcount=count($result);

        for($i=0; $i < $this->_colcount; $i++){
            $str.='<td>'. $this->_renderInlineTag($result[$i]).'</td>';
        }
        $str=$t.'<tr>'.$str.'</tr>';

        return $str;
    }

}

/**
* traite les signes de types hr
*/
class WRB_hr extends CopixWikiRendererBloc
{
    public $type='hr';
    public $regexp='/^={4,} *$/';
    public $_closeNow=true;

    public function getRenderedLine()
    {
        return '<hr />';
    }

}

/**
* traite les signes de types titre
*/
class WRB_title extends CopixWikiRendererBloc
{
    public $type='title';
    public $regexp="/^(\!{1,3})(.*)/";
    public $_closeNow=true;
    public $_minlevel=1;
    public $_order=false;

    public function WRB_title(&$wr)
    {
        $this->_minlevel = $wr->config->minHeaderLevel;
        $this->_order = $wr->config->headerOrder;
        parent::CopixWikiRendererBloc($wr);
    }

    public function getRenderedLine()
    {
        if($this->_order)
        $hx= $this->_minlevel + strlen($this->_detectMatch[1])-1;
        else
        $hx= $this->_minlevel + 3-strlen($this->_detectMatch[1]);
        return '<h'.$hx.'>'.$this->_renderInlineTag($this->_detectMatch[2]).'</h'.$hx.'>';
    }
}

/**
* traite les signes de type paragraphe
*/
class WRB_p extends CopixWikiRendererBloc
{
    public $type='p';
    public $regexp="/(.*)/";
    public $_openTag='<p>';
    public $_closeTag='</p>';
}

/**
* traite les signes de types pre (pour afficher du code..)
*/
class WRB_pre extends CopixWikiRendererBloc
{
    public $type='pre';
    public $regexp="/^ (.*)/";
    public $_openTag='<pre>';
    public $_closeTag='</pre>';

    public function getRenderedLine()
    {
        return $this->_renderInlineTag($this->_detectMatch[1]);
    }

}


/**
* traite les signes de type blockquote
*/
class WRB_blockquote extends CopixWikiRendererBloc
{
    public $type='bq';
    public $regexp="/^(\>+)(.*)/";

    public function open()
    {
        $this->_previousTag = $this->_detectMatch[1];
        $this->_firstTagLen = strlen($this->_previousTag);
        $this->_firstLine = true;
        return str_repeat('<blockquote>',$this->_firstTagLen).'<p>';
    }

    public function close()
    {
        return '</p>'.str_repeat('</blockquote>',strlen($this->_previousTag));
    }


    public function getRenderedLine()
    {
        $d=strlen($this->_previousTag) - strlen($this->_detectMatch[1]);
        $str='';

        if( $d > 0 ){ // on remonte d'un cran dans la hierarchie...
        $str='</p>'.str_repeat('</blockquote>',$d).'<p>';
        $this->_previousTag=$this->_detectMatch[1];
        }elseif( $d < 0 ){ // un niveau de plus
        $this->_previousTag=$this->_detectMatch[1];
        $str='</p>'.str_repeat('<blockquote>',-$d).'<p>';
        }else{
            if($this->_firstLine)
            $this->_firstLine=false;
            else
            $str='<br />';
        }
        return $str.$this->_renderInlineTag($this->_detectMatch[2]);
    }
}

/**
* traite les signes de type blockquote
*/
class WRB_definition extends CopixWikiRendererBloc
{
    public $type='dfn';
    public $regexp="/^;(.*) : (.*)/i";
    public $_openTag='<dl>';
    public $_closeTag='</dl>';

    public function getRenderedLine()
    {
        $dt=$this->_renderInlineTag($this->_detectMatch[1]);
        $dd=$this->_renderInlineTag($this->_detectMatch[2]);
        return "<dt>$dt</dt>\n<dd>$dd</dd>\n";
    }
}

