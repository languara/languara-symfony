<?php

namespace Languara\SymfonyBundle\Wrapper;

class LanguaraWrapper extends \Languara\Library\Lib_Languara
{    
    static $app_dir_path;
    public static function get_instance($app_dir_path = null)
    {        
        static $instance = null;
        if ($instance == null) $instance = new static();
                
        if ($app_dir_path)
        {
            static::$app_dir_path = $app_dir_path;
            $instance->language_location = $app_dir_path . $instance->language_location;
            $instance->driver->set_language_location($instance->language_location);
        }
        
        return $instance;
    }
    public function __construct()
    {
        parent::__construct();
        
        $config = array();
        
        try
        {
            $static_resources = include_once __DIR__ .'/../Resources/config/static_resources.php';
            $config = include_once __DIR__ .'/../Resources/config/languara.php';
        }
        catch(\Exception $e){}
        
        $this->language_location = (isset($config['language_location'])) ? $config['language_location'] : null;
        $this->conf = (isset($config['conf'])) ? $config['conf'] : null;
        $this->endpoints = $static_resources['endpoints'];
        $this->arr_messages = $static_resources['messages'];
        $this->origin_site = $static_resources['origin_site'];
        $this->config_files = $static_resources['config_files'];
        $this->platform = $static_resources['platform'];
        $this->driver = new \Languara\SymfonyBundle\Driver\SymfonyResourceGroupDriver();        
        $this->driver->set_storage_engine($this->conf['storage_engine']);
        
        // change config files to have absoluth paths
        if (is_array($this->config_files))
        {
            foreach ($this->config_files as $config_key => $config_file)
            {
                $this->config_files[$config_key] = __DIR__ .'/../Resources/'. $config_file;
            }
        }
    }
    
    protected function load_config_file()
    {
        try
        {
            $config = include_once __DIR__ .'/../Resources/config/languara.php';
        }
        catch(\Exception $e){}
        
        $this->language_location = (isset($config['language_location'])) ? static::$app_dir_path . $config['language_location'] : null;
        $this->conf = (isset($config['conf'])) ? $config['conf'] : null;
    }
    
    protected function retrieve_resource_groups_and_locales()
	{
        $arr_data = array();
        $arr_data['files'] = array();
        $iterator = new \FilesystemIterator($this->language_location);
        $extension = null;
        
        switch ($this->conf['storage_engine'])
        {
            case 'php_array':
                $extension = 'php';
                break;
            
            case 'yaml':
                $extension = 'yml';
                break;
            case 'xliff':
                $extension = 'xml';
                break;
            default:
                throw new \Exception($this->get_message_text('error_storage_engine'));
        }
        
        $filter = new \RegexIterator($iterator, '/.('. $extension .')$/');
        
        foreach($filter as $file) 
        {
            $name = $file->getBasename('.'. $extension);
            list($resource_group, $locale) = explode('.', $name);
            
            $arr_data[$locale][$resource_group] = array('resource_group_name' => $resource_group, 'file_path' => $file->getPathname());
        }
        
        return $arr_data;
	}
}