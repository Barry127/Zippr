zippr
=====

A PHP class for handeling zip files. The class abstracts the ZipArchive class.

Current support:
- Add file to archive
- Add directory to archive
- Add comment to archive
- Extract an archive


UPDATED
-------

Zip is now called Zippr and carries version number 1.0.

This major update has the following updates

- PSR-0 standard for namespacing
- Error handling using PHP Exception class
- Bug fixes on addDir method
- extractArchive has become a static function
- Dependencies are checked in a seperate method


INSTALL
------

Add the next line to the require section in your composer.json file

    "barry127/zippr": "1.*"