zip
===

A PHP class for handeling zip files. The class abstracts the ZipArchive class.

Current support:
- Add file to archive
- Add directory to archive
- Add comment to archive
- Extract an archive

How to use
---------

Include the zip class to your php file and create an object variable to use it like:

**PHP**
    <?php
    require_once 'zip.class.php';
    
    $zip = new zip('nameForYourZipFile.zip');
    
    $zip->addFile('someFile');
    
    unset($zip);
    ?>

Make sure your the destination directory for your zip file is CHMOD properly.

FUNCTIONS
---------

**construct($name)**

Construct class and check independencies

param $name (_string_) Relative path for new zip archive.

return (_bool_) Successful


**destruct()**

Desctruct class and clean up.

return (_null_)


**addDir($dir, $location)**

Add directory to the zip archive

param $dir (_string_) Relative path to the dir to add

param $location (_string_) Destination directory in the zip archive to add dir (default: _null_)

return (_bool_) on successful


**addFile($file, $location)**

Add file to the zip archive

param $file (_string_) Relative path to the file to add

param $location (_string_) Destination directory in the zip archive to add file (default: _null_)

return (_bool_) on successful


**extractArchive($archive, $location)**

Extract a zip archive to a directory

param $archive (_string_) Relative path to the archive to extract

param $location (_string_) Destination directory to extract to (default: _null_)

return (_bool_) on successful


**setComment($comment)***

Set archive comment

param $comment (_string_) Comment for archive (default: _null_)

return (_bool_) on successful


**getVersion()**

Get current class version

return (_string_) Current class version
