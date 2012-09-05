<?php

if(!isset($argv[1]))
    die('Il manque le nom du module (casse sensitive)'.PHP_EOL);

$nomModule = $argv[1];

$default_actiongroup = <<<EOT
<?php

    class ActionGroupDefault extends enicActionGroup
    {
        public function __construct()
        {
            parent::__construct();
            \$this->service =& \$this->service('${nomModule}Service');
        }

        public function beforeAction ()
        {
        _currentUser()->assertCredential ('group:[current_user]');
        }

        public function processDefault()
        {
            \$ppo = new CopixPPO();

            return _arPPO(\$ppo, 'default.tpl');
        }

    }
EOT;

$default_moduleservice = <<<EOT
<?php

    class ${nomModule}Service extends enicService{

    }
EOT;

$default_properties = <<<EOT
   <
EOT;

$default_template = <<<EOT
    <h3>New Enic Module Initialized</h3>
EOT;

$default_modulexml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<moduledefinition>
    <general>
        <default name="${nomModule}" />
    </general>
    <parameters>
       </parameters>
</moduledefinition>
EOT;

$dir = array('actiongroups', 'classes', 'resources', 'templates', 'zones');
$rootDir = '../../../project/modules/public/stable/iconito/';

//makes dir
if(!is_dir($rootDir))
    if(!mkdir($rootDir.strtolower($nomModule)))
        die('fail to create root directory'.PHP_EOL);

$projectDir = $rootDir.strtolower($nomModule).'/';

foreach($dir as $d){
    if(!is_dir($projectDir.$d))
        if(!mkdir($projectDir.$d))
            die('fail to create '.$d.PHP_EOL);
}

//push datas :
file_put_contents($projectDir.'actiongroups/default.actiongroup.php', $default_actiongroup);
file_put_contents($projectDir.'classes/'.strtolower($nomModule).'service.class.php', $default_moduleservice);
file_put_contents($projectDir.'resources/'.  strtolower($nomModule).'_fr.properties', $default_properties);
file_put_contents($projectDir.'templates/default.tpl', $default_template);
file_put_contents($projectDir.'module.xml', $default_modulexml);
echo 'opération réussi, il vous reste à ajouter la ligne "kernel.codes.mod_'.$nomModule.'" dans le fichier kernel_fr.properties '.PHP_EOL;


