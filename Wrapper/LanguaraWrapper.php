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
        }
        
        return $instance;
    }
    protected function __construct()
    {
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
    
    protected function retrieve_local_translations()
	{
		// get local locales, resource groups, and translations
		$dir_iterator = new \DirectoryIterator($this->language_location);		
		$arr_locales = array();
		
		foreach ($dir_iterator as $file)
		{	
			// skip the system files for navigation and language_backup directory
			if($file->getFilename() != '.' && $file->getFilename() != '..' && $file->getFilename() != 'language_backup')
			{
				if (! $file->isDir())
				{                                     
                    $lang = include ($file->getRealPath());

                    if (! isset($lang)) continue;
                    
                    // the name of the files in symfony is resource_group.locale_name_eng,
                    // we need to explode the name and get the parts
                    
                    $arr_filename_parts     = explode('.', $file->getFilename());
					$resource_group_name    = $arr_filename_parts[0];
                    $locale                 = $arr_filename_parts[1];
                    
                    // count resource groups and translations
                    if (! isset($this->resource_groups[$resource_group_name])) $this->resource_groups[$resource_group_name] = $resource_group_name;
                    $this->translations_count += count($lang);

                    // validate resource_cd
                    foreach ($lang as $resource_cd => $translation)
                    {
                        if (! $this->validate_resource_code($resource_cd) && ! isset($this->invalid_resource_cds[$resource_cd])) 
                            $this->invalid_resource_cds[$resource_cd] = array('resource_cd' => $resource_cd, 'resource_group_name' => $resource_group_name);
                    }
                    
					$arr_locales[$locale][$resource_group_name] = $lang;
				}
			}
		}
        
        return $arr_locales;
	}
    
    protected function add_translations_to_files()
    {
        // process locale
		foreach ($this->arr_project_locales as $project_locale) 
		{			
			// process translations
			foreach ($this->arr_resource_groups as $resource_group)
			{
                
				$resource_group_file_contents = $this->get_file_header();
				foreach ($this->arr_translations as $translation) 
				{					
					if ($translation->resource_group_id == $resource_group->resource_group_id && $project_locale->locale_id == $translation->locale_id)
					{						
						$resource_group_file_contents .= $this->get_file_content($translation->resource_cd, $translation->translation_txt);
					}
				}
                
                $resource_group_file_contents .= $this->get_file_footer();
                
                $file_path = $this->language_location . strtolower($this->conf['file_prefix'] . $resource_group->resource_group_name .'.'. $project_locale->iso_639_1 . $this->conf['file_suffix'] .'.php');
                
				file_put_contents($file_path, $resource_group_file_contents);
                chmod($file_path, 0777);
			}
		}	
    }
}