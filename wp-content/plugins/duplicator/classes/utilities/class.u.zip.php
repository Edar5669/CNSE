<?php
defined('ABSPATH') || defined('DUPXABSPATH') || exit;
/**
 * Utility class for zipping up content
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @subpackage classes/utilities
 * @copyright (c) 2017, Snapcreek LLC
 * @license	https://opensource.org/licenses/GPL-3.0 GNU Public License
 */

// Exit if accessed directly
if (! defined('DUPLICATOR_VERSION')) exit;

class DUP_Zip_U
{
    /**
     * Add a directory to an existing ZipArchive object
     *
     * @param ZipArchive $zipArchive        An existing ZipArchive object
     * @param string     $directoryPath     The full directory path to add to the ZipArchive
     * @param bool       $retainDirectory   Should the full directory path be retained in the archive
     *
     * @return bool Returns true if the directory was added to the object
     */
	public static function addDirWithZipArchive(&$zipArchive, $directoryPath, $retainDirectory, $localPrefix, $isCompressed)
    {
        $success = true;

        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directoryPath), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($objects as $path => $object) {
            $path       = DUP_Util::safePath($path);
            $local_name = ltrim(str_replace($directoryPath, '', $path), '/');

            if ($retainDirectory) {
                $local_name = basename($directoryPath)."/$local_name";
            }

            $local_name = $localPrefix . $local_name;

            if (!is_dir($path)) {
                if (is_readable($path)) {
                       $added = DUP_Zip_U::addFileToZipArchive($zipArchive, $path, $local_name, $isCompressed);
                } else {
                    $added = false;
                }
            } else {
                $added = true;
            }

            if (!$added) {
                DUP_Log::error("Couldn't add file $path to archive", '', Dup_ErrorBehavior::LogOnly);
                $success = false;
                break;
            }
        }

        return $success;
    }


    public static function extractFiles($archiveFilepath, $relativeFilesToExtract, $destinationDirectory, $useShellUnZip)
    {
        // TODO: Unzip using either shell unzip or ziparchive
        if($useShellUnZip) {
            $shellExecPath = DUPX_Server::get_unzip_filepath();
            $filenameString = implode(' ', $relativeFilesToExtract);
            $command = "{$shellExecPath} -o -qq \"{$archiveFilepath}\" {$filenameString} -d {$destinationDirectory} 2>&1";
            $stderr = shell_exec($command);

            if ($stderr != '') {
                $errorMessage = DUP_U::__("Error extracting {$archiveFilepath}): {$stderr}");

                throw new Exception($errorMessage);
            }
        } else {
            $zipArchive = new ZipArchive();
            $result = $zipArchive->open($archiveFilepath);

            if($result !== true) {
                throw new Exception("Error opening {$archiveFilepath} when extracting. Error code: {$retVal}");
            }

            $result = $zipArchive->extractTo($destinationDirectory, $relativeFilesToExtract);

            if($result === false) {
                throw new Exception("Error extracting {$archiveFilepath}.");
            }
        }
    }

    /**
     * Add a directory to an existing ZipArchive object
     *
     * @param string    $sourceFilePath     The file to add to the zip file
     * @param string    $zipFilePath        The zip file to be added to
     * @param bool      $deleteOld          Delete the zip file before adding a file
     * @param string    $newName            Rename the $sourceFile if needed
     *
     * @return bool Returns true if the file was added to the zip file
     */
	public static function zipFile($sourceFilePath, $zipFilePath, $deleteOld, $newName, $isCompressed)
    {
        if ($deleteOld && file_exists($zipFilePath)) {
            DUP_IO::deleteFile($zipFilePath);
        }

        if (file_exists($sourceFilePath)) {
            $zip_archive = new ZipArchive();

            $is_zip_open = ($zip_archive->open($zipFilePath, ZIPARCHIVE::CREATE) === TRUE);

            if ($is_zip_open === false) {
                DUP_Log::error("Cannot create zip archive {$zipFilePath}");
            } else {
                //ADD SQL
                if ($newName == null) {
                    $source_filename = basename($sourceFilePath);
                    DUP_Log::Info("adding {$source_filename}");
                } else {
                    $source_filename = $newName;
                    DUP_Log::Info("new name added {$newName}");
                }

                $in_zip = DUP_Zip_U::addFileToZipArchive($zip_archive, $sourceFilePath, $source_filename, $isCompressed);

                if ($in_zip === false) {
                    DUP_Log::error("Unable to add {$sourceFilePath} to $zipFilePath");
                }

                $zip_archive->close();

                return true;
            }
        } else {
            DUP_Log::error("Trying to add {$sourceFilePath} to a zip but it doesn't exist!");
        }

        return false;
    }

    public static function addFileToZipArchive(&$zipArchive, $filepath, $localName, $isCompressed)
    {
		$added = $zipArchive->addFile($filepath, $localName);
        return $added;
    }
}