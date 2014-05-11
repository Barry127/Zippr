<?php
/**
 * Class to handle zip files
 * 
 * @author      Barry de Kleijn
 * @copyright   Barry de Kleijn
 * @license 	MIT
 * @license 	http://opensource.org/licenses/MIT MIT
 *
 * @version     1.0.0
 */

namespace Barry127\Zippr;

class Zippr
{
    /**
     * Zippr version
     *
     * @var string
     */
	const 	VERSION 	= '1.0.0';

	/**
	 * Name of the zip file
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Zip object
	 * 
	 * @var object
	 */
	private $zip;


	/**
	 * Zippr constructor
	 *
	 * @param 	string 	$name
     * 
     * @return  void
	 */
	public function __construct($name = null)
	{
		$this->checkDependencies();

		if(is_null($name) || strlen($name) < 1) {
			$name = uniqid(true) . '.zip';
		} elseif(strtolower(substr($name, -4)) != '.zip') {
			$name .= '.zip';
		}

		$this->name = $name;
		$this->zip  = new \ZipArchive;

		if(!$this->zip->open($this->name, \ZipArchive::CREATE) === true) {
			throw new \Exception("Could not create zip file!", 1);
		}
	}

	/**
	 * Zippr destructor
     * 
     * Zippr automatically closes the zip file at destruct so it can be downloaded by the client.
     * 
     * @return  void
	 */
	public function __destruct() {
		$this->zip->close();
	}

	/**
	 * Check for class dependencies
     * 
     * @return  void
	 */
	private function checkDependencies()
	{
		if(version_compare(PHP_VERSION, '5.3.0', '<')) {
			throw new \Exception('Zippr requires PHP 5.3.0 or higher.', 0);
		}

		if(!class_exists('ZipArchive')) {
			throw new \Exception("Could not construct zip file: Missing library ZipArchive.", 0);
		}
	}

	/**
	 * Add directory to zip archive
	 *
	 * @param 	string 	$dir        Directory to add to archive
	 * @param 	string 	$location   Location in archive
     * 
     * return   void
	 */
	public function addDir($dir, $location = null)
	{
		if(!is_dir($dir)) return $this->addFile($dir);

		if(is_null($dir) || strlen($dir) < 1) $dir = '.';
		if(substr($dir, -1) != DIRECTORY_SEPARATOR) $dir .= DIRECTORY_SEPARATOR;
		if(substr($location, -1) != DIRECTORY_SEPARATOR) $location .= DIRECTORY_SEPARATOR;

		$objDir = opendir($dir);
		while($file = readdir($objDir)) {
			if($file != '.' && $file != '..') {
				if(is_dir($dir . $file)) {
					$this->addDir($dir . $file, $location . $file);
				} else {
					$this->addFile($dir . $file, $location);
				}
			}
		}
	}

	/**
	 * Add file to zip archive
	 * 
	 * @param 	string 	$file       File to add to archive
	 * @param 	string 	$location   Location in archive
     * 
     * @return  void
	 */
	public function addFile($file, $location = null)
	{
		if(!file_exists($file)) return;
		if(is_dir($file)) return $this->addDir($file, $location);

		if(substr($location, -1) != DIRECTORY_SEPARATOR) $location .= DIRECTORY_SEPARATOR;

		if(!@$this->zip->addFile($file, $location . pathinfo($file, PATHINFO_BASENAME)) === true) {
			throw new \Exception("Could not add $file to archive", 3);
		}
	}

	/**
	 * Extract zip archive
	 *
	 * @param 	string 	$archive    Archive to extract
	 * @param 	string 	$location   Location to extract to
     * 
     * @return  void
	 */
	public static function extractArchive($archive, $location = null)
	{
		Zippr::checkDependencies();

		if(!file_exists($archive) || strtolower(substr($archive, -4)) != '.zip') {
			throw new \Exception("Invalid Archive", 2);
		}
		if(is_null($location) || strlen($location) < 1) $location = '.';
		if(substr($location, -1) != DIRECTORY_SEPARATOR) $location .= DIRECTORY_SEPARATOR;

		$zip = new \ZipArchive;

		if(!$zip->open($archive) === true) {
			throw new \Exception("Could not open zip file!", 1);
		}
		if(!@$zip->extractTo($location) === true) {
			throw new \Exception("Could not extract zip file!", 3);
		}

		$zip->close();
	}

	/**
	 * Set archive comment
	 *
	 * @param 	string 	$comment    Comment for archive
     * 
     * @return  void
	 */
	public function setComment($comment) {
		$this->zip->setArchiveComment($comment);
	}

	/**
	 * Get class version
     * 
     * @return  string  Zippr version
	 */
	public function getVersion() {
		return self::VERSION;
	}
}