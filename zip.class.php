<?php

/**
 * Class to handle zip files
 * 
 * @author 		Barry de Kleijn (kleijn.barry@gmail.com)
 * @copyright	MIT Licence
 * 
 * @version 	1.2.0
 */

class zip {

	const   VERSION 	= '1.2.0';

	private $name		= ''; //name of the zip file
	private $zip		= null; //zip object

	/**
	 * Class constructor
	 * 
	 * @access 	public
	 * @version 1.2.0
	 * @since   1.0.0
	 * 
	 * @param 	string 	$name 	Name of the zipfile
	 *
	 * @return 	bool 			Successful
	 */
	public function __construct($name = null) {
		if(version_compare(PHP_VERSION,'5.2.0','<')) {
			echo '<strong>Warning:</strong> zip requires PHP 5.2.0 or higher.';
			return false;
		}
		if(!class_exists('ZipArchive')) {
			echo '<strong>Warning</strong> Could not construct zip file! Please install the ZipArchive library';
			return false;
		}

		if($name == null || strlen($name) < 1) {
			$name = uniqid(true) . '.zip';
		} elseif(strtolower(substr($name,-4)) != '.zip') {
			$name .= '.zip';
		}

		$this->name = $name;
		$this->zip  = new ZipArchive;

		if(!$this->zip->open($this->name, ZipArchive::CREATE) === true) {
			echo '<strong>Warning:</strong> Could not create zip file!';
		}

		return true;
	}

	/**
	 * Destruct class
	 * 
	 * @access 	public
	 * @version 1.2.0
	 * @since 	1.0.0
	 *
	 * @return 	null
	 */
	public function __destruct() {
		if(class_exists('ZipArchive')) {
			$this->zip->close();
		}
	}

	/**
	 * Add directory to zip archive
	 * 
	 * @access 	public
	 * @version 1.2.0
	 * @since 	1.0.0
	 *
	 * @param 	string 	$dir 		The directory + relative path to add
	 * @param 	string 	$location 	The destination directory in the archive
	 * 
	 * @return 	bool 				Successful
	 */
	public function addDir($dir,$location = null) {
		if(!is_dir($dir)) {
			if(file_exists($dir)) {
				return $this->addFile($dir,$location);
			}
			return false;
		}
		if(strlen($dir) > 1 && substr($dir,-1) != DIRECTORY_SEPARATOR) {
			$dir .= DIRECTORY_SEPARATOR;
		}
		if(substr($location,-1) != DIRECTORY_SEPARATOR) {
			$location .= DIRECTORY_SEPARATOR;
		}

		$objDir = opendir($dir);
		while($file = readdir($objDir)) {
			if($file != '.' && $file != '..') {
				if(is_dir($dir . $file)) {
					$this->addDir($dir . $file,$location);
				} else {
					$this->addFile($dir . $file, $location . $dir);
				}
			}
		}
		closedir($objDir);
		return true;
	}

	/**
	 * Add file to the zip archive
	 * 
	 * @access 	public
	 * @version 1.2.0
	 * @since 	1.0.0
	 * 
	 * @param 	string 	$file 		The file + relative path to add
	 * @param 	string 	$location 	The destination directory in the archive
	 *
	 * @return 	bool 				Successful
	 */
	public function addFile($file, $location = null) {
		if(!file_exists($file)) {
			return false;
		}
		if(is_dir($file)) {
			return $this->addDir($file,$location);
		}
		if(substr($location,-1) != DIRECTORY_SEPARATOR) {
			$location .= DIRECTORY_SEPARATOR;
		}
		if(!@$this->zip->addFile($file,$location . pathinfo($file,PATHINFO_FILENAME) . '.' . pathinfo($file,PATHINFO_EXTENSION))) {
			return false;
		}
		return true;
	}

	/**
	 * Extract a zip archive
	 * 
	 * @access 	public
	 * @version 1.2.0
	 * @since 	1.0.0
	 *
	 * @param 	string 	$archive 	Archive to extract (relative path)
	 * @param 	string 	$location 	Location directory to extract to (relative path)
	 * 
	 * @return 	bool 				Successful
	 */
	public function extractArchive($archive, $location = null) {
		if(!file_exists($archive) || strtolower(substr($archive,-4)) != '.zip') {
			return false;
		}
		if($location == null || strlen($location) < 1) {
			$location = '.';
		}
		if(substr($location,-1) == DIRECTORY_SEPARATOR) {
			$location = substr($location,0,-1);
		}
		$zip = new ZipArchive;

		if(!$zip->open($archive) === true) {
			return false;
		}
		if(!@$zip->extractTo($location)) {
			return false;
		}
		$zip->close();
		return true;
	}

	/**
	 * Set archive comment
	 *
	 * @access 	public
	 * @version 1.2.0
	 * @since 	1.0.0
	 * 
	 * @param 	string 	$comment 	Archive comment
	 *
	 * @return 	bool 				Successful
	 */
	public function setComment($comment = null) {
		$this->zip->setArchiveComment($comment);
		return true;
	}

	/**
     * get current class version
     *
     * @access  public
     * @version 1.2.0
     * @since   1.1.0
     *
     * @return  string  Current version of this class
     */
    public function getVersion() {
        return self::VERSION;
    }
}
?>