<?php

namespace Languara\SymfonyBundle\Driver;

use Symfony\Component\Translation\Dumper;
use Symfony\Component\Translation\Loader;
use Symfony\Component\Translation\MessageCatalogue;

class SymfonyResourceGroupDriver implements ResourceGroupDriverInterface
{
    private $language_location = null;
    private $storage_engine = null;
    
    public function load($resource_group_name, $lang_name = null, $file_path = null)
    {               
        switch ($this->storage_engine)
        {
            case 'php_array':
                $loader = new Loader\PhpFileLoader();
                break;
            
            case 'yaml':
                $loader = new Loader\YamlFileLoader();
                break;
            
            case 'xliff':
                $loader = new Loader\XliffFileLoader();
                break;
            default:
                return 'error_storage_engine';
        }
        
        $unique_domain = $resource_group_name .'.'. $lang_name;
        
        return current($loader->load($file_path, $lang_name, $unique_domain)->all());
    }

    public function save($resource_group_name, $arr_translations, $lang_name = null, $file_path = null)
    {
        $catalogue = new MessageCatalogue($lang_name, array($resource_group_name => $arr_translations));
        
        switch ($this->storage_engine)
        {
            case 'php_array':
                $dumper = new Dumper\PhpFileDumper();
                break;
            
            case 'yaml':
                $dumper = new Dumper\YamlFileDumper();
                break;
            
            case 'xliff':
                $dumper = new Dumper\XliffFileDumper();
                break;
            default:
                return 'error_storage_engine';
        }
        
        $dumper->dump($catalogue, array('path' => $this->language_location));
        
        return true;
    }
    
    public function set_language_location($language_location)
    {
        $this->language_location = $language_location;
    }
    
    public function set_storage_engine($storage_enginge)
    {
        $this->storage_engine = $storage_enginge;
    }

}