<?php
/**
 * This is the post install setup script for the frontend.
 * 
 * It takes in responses to questions and handles updating the default
 * template as well as creating a sample index page.
 * 
 * @package UNL_UCBCN_Frontend
 * @author bbieber
 * 
 */

class UNL_UCBCN_Frontend_setup_postinstall
{
	var $createFiles;
	var $createIndex;
	var $dsn;
	
	function init(&$config, &$pkg, $lastversion)
    {
        $this->_config = &$config;
        $this->_registry = &$config->getRegistry();
        $this->_ui = &PEAR_Frontend::singleton();
        $this->_pkg = &$pkg;
        $this->lastversion = $lastversion;
        $this->databaseExists = false;
        return true;
    }
    
    function postProcessPrompts($prompts, $section)
    {
        switch ($section) {
            case 'databaseSetup' :
                
            break;
        }
        return $prompts;
    }
    
    function run($answers, $phase)
    {
        switch ($phase) {
        	case 'questionCreate':
        		$this->createFiles		= ($answers['createtemplate']=='yes')?true:false;
        		$this->createIndex		= ($answers['createindex']=='yes')?true:false;
    			return $this->createFiles;
            case 'fileSetup' :
            	if ($this->createFiles) {
               		return $this->createFiles($answers);
            	} else {
            		return true;
            	}
            case '_undoOnError' :
                // answers contains paramgroups that succeeded in reverse order
                foreach ($answers as $group) {
                    switch ($group) {
                        case 'createFiles' :
                        break;
                    }
                }
            break;
        }
    }
    
    /**
     * This function creates/upgrades the template files, as well as the sample index page.
     */
    function createFiles($answers)
    {
    	// Copy the template files over to the location they answered.
    	$docroot = $answers['docroot'].DIRECTORY_SEPARATOR;
		$templateroot = $docroot.'templates'.DIRECTORY_SEPARATOR.$answers['template'].DIRECTORY_SEPARATOR;
		$datadir = '@DATA_DIR@'. DIRECTORY_SEPARATOR . 'UNL_UCBCN_Frontend' . DIRECTORY_SEPARATOR;
		if ($this->createIndex) {
			copy($datadir.'index.php', $docroot.'index.php');
		}
		
		$this->dircpy($datadir.'templates', $docroot.'templates', true);
		return self::file_str_replace(
    				array(	'@DSN@',
    						//'@URI@',
    						'@'.'TEMPLATE@',
    						'@'.'DOCROOT@'),
    				array(	$this->dsn,
    						//$answers['uri'],
    						$answers['template'],
    						$docroot),
					array(	$docroot.'index.php',
							$templateroot.'Frontend.php')
							);
    }
    
    /**
     * Replaces text within files.
     * 
     * @param mixed $search An array or string of text to search for.
     * @param mixed $replace An array or string of text to replace the matched text with.
     * @param mixed $file An array or string of filenames to search and replace text in.
     */
    function file_str_replace($search,$replace,$file)
    {
    	$a = true;
    	if (is_array($file)) {
    		foreach ($file as $f) {
    			$a = self::file_str_replace($search,$replace,$f);
    			if ($a != true) {
    				return $a;
    			}
    		}
    	} else {
    		if (file_exists($file)) {
				$contents = file_get_contents($file);
				$contents = str_replace($search,$replace,$contents);
	
				$fp = fopen($file, 'w');
				$a = fwrite($fp, $contents, strlen($contents));
				fclose($fp);
				if ($a) {
					$this->_ui->outputData($file);
					return true;
				} else {
					$this->_ui->outputData('Could not update ' . $file);
					return false;
				}
    		} else {
    			$this->_ui->outputData($file.' does not exist!');
    		}
    	}
    }
    
    /**
     * This function copies files from source to destination.
     * 
     */
    function dircpy($source, $dest, $overwrite = false){
		if($handle = opendir($source)) {        // if the folder exploration is sucsessful, continue
			if (!is_dir($dest)) {
				mkdir( $dest );
			}
			while(false !== ($file = readdir($handle))) { // as long as storing the next file to $file is successful, continue
				if($file != '.' && $file != '..') {
					$path = $source . DIRECTORY_SEPARATOR . $file;
					if(is_file( $path)) {
						if(!is_file( $dest . DIRECTORY_SEPARATOR . $file) || $overwrite) {
							if(!copy( $path, $dest . DIRECTORY_SEPARATOR . $file)){
								$this->_ui->outputData('File ('.$path.') could not be copied, likely a permissions problem.');
							}
						}
					} elseif(is_dir( $path)){
						if(!is_dir( $dest . DIRECTORY_SEPARATOR . $file)) {
							mkdir( $dest . DIRECTORY_SEPARATOR . $file); // make subdirectory before subdirectory is copied
						}
						$this->dircpy($path, $dest . DIRECTORY_SEPARATOR . $file, $overwrite); //recurse!
					}
				}
			}
			closedir($handle);
		} else {
			$this->_ui->outputData('Could not open '.$source);
			return false;
		}
	}
}
?>