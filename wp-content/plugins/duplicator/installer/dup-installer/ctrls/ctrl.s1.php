<?php
defined('ABSPATH') || defined('DUPXABSPATH') || exit;

/** IDE HELPERS */
/* @var $GLOBALS['DUPX_AC'] DUPX_ArchiveConfig */

//OPTIONS
$base_file_perms_value		= (isset($_POST['file_perms_value'])) ? $_POST['file_perms_value'] : 'not set';
$base_dir_perms_value		= (isset($_POST['dir_perms_value']))  ? $_POST['dir_perms_value']  : 'not set';
$_POST['set_file_perms']	= (isset($_POST['set_file_perms']))   ? 1 : 0;
$_POST['set_dir_perms']		= (isset($_POST['set_dir_perms']))    ? 1 : 0;
$_POST['file_perms_value']	= (isset($_POST['file_perms_value'])) ? intval(('0' . $_POST['file_perms_value']), 8) : 0755;
$_POST['dir_perms_value']	= (isset($_POST['dir_perms_value']))  ? intval(('0' . $_POST['dir_perms_value']), 8)  : 0644;
$_POST['zip_filetime']		= (isset($_POST['zip_filetime']))     ? $_POST['zip_filetime'] : 'current';
$_POST['config_mode']		= (isset($_POST['config_mode']))      ? $_POST['config_mode'] : 'NEW';
$_POST['archive_engine']	= (isset($_POST['archive_engine']))   ? $_POST['archive_engine'] : 'manual';
$_POST['exe_safe_mode']		= (isset($_POST['exe_safe_mode']))    ? $_POST['exe_safe_mode'] : 0;

//LOGGING
$POST_LOG = $_POST;
unset($POST_LOG['dbpass']);
ksort($POST_LOG);

if($_POST['archive_engine'] == 'manual') {
	$GLOBALS['DUPX_STATE']->isManualExtraction = true;
	$GLOBALS['DUPX_STATE']->save();
}

//ACTION VARS
$ajax1_start		= DUPX_U::getMicrotime();
$root_path			= $GLOBALS['DUPX_ROOT'];
$wpconfig_ark_path	= ($GLOBALS['DUPX_AC']->installSiteOverwriteOn) ?
						"{$root_path}/dup-wp-config-arc__{$GLOBALS['DUPX_AC']->package_hash}.txt"
					:	"{$root_path}/wp-config.php";

$archive_path		= $GLOBALS['FW_PACKAGE_PATH'];
$JSON				= array();
$JSON['pass']		= 0;

/** JSON RESPONSE: Most sites have warnings turned off by default, but if they're turned on the warnings
  cause errors in the JSON data Here we hide the status so warning level is reset at it at the end */
$ajax1_error_level = error_reporting();
error_reporting(E_ERROR);

$nManager = DUPX_NOTICE_MANAGER::getInstance();

//===============================
//ARCHIVE ERROR MESSAGES
//===============================
($GLOBALS['LOG_FILE_HANDLE'] != false) or DUPX_Log::error(ERR_MAKELOG);

if (! $GLOBALS['DUPX_AC']->exportOnlyDB) {

	$post_archive_engine = DUPX_U::sanitize_text_field($_POST['archive_engine']);

	if ($post_archive_engine == 'manual'){
		if (!file_exists($wpconfig_ark_path) && !file_exists("database.sql")) {
			DUPX_Log::error(ERR_ZIPMANUAL);
		}
	} else {
        if (!is_readable("{$archive_path}")) {
			DUPX_Log::error("archive path:{$archive_path}<br/>" . ERR_ZIPNOTFOUND);
		}
	}

	//ERR_ZIPMANUAL
	if ('ziparchive' == $post_archive_engine && !$GLOBALS['DUPX_AC']->installSiteOverwriteOn) {
		//ERR_CONFIG_FOUND
		$outer_root_path = dirname($root_path);
		
		if ((file_exists($wpconfig_ark_path) || (@file_exists("{$outer_root_path}/wp-config.php") && !@file_exists("{$outer_root_path}/wp-settings.php"))) && @file_exists("{$root_path}/wp-admin") && @file_exists("{$root_path}/wp-includes")) {
			DUPX_Log::error(ERR_CONFIG_FOUND);
		}
	}
}

DUPX_Log::info("********************************************************************************");
DUPX_Log::info('* DUPLICATOR-PRO: Install-Log');
DUPX_Log::info('* STEP-1 START @ '.@date('h:i:s'));
DUPX_Log::info("* VERSION: {$GLOBALS['DUPX_AC']->version_dup}");
DUPX_Log::info('* NOTICE: Do NOT post to public sites or forums!!');
DUPX_Log::info("********************************************************************************");

$colSize = 60;
$os      = defined('PHP_OS') ? PHP_OS : 'unknown';
$log     = str_pad(str_pad('PACKAGE INFO', 13, '_', STR_PAD_RIGHT).' '.'CURRENT SERVER', $colSize, ' ', STR_PAD_RIGHT).'| '.'ORIGINAL SERVER'."\n".
    str_pad(str_pad('PHP VERSION', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->version_php, $colSize, ' ', STR_PAD_RIGHT).'| '.phpversion()."\n".
    str_pad(str_pad('OS', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->version_os, $colSize, ' ', STR_PAD_RIGHT).'| '.$os."\n".
    str_pad('CREATED', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->created."\n".
    str_pad('WP VERSION', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->version_wp."\n".
    str_pad('DUP VERSION', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->version_dup."\n".
    str_pad('DB', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->version_db."\n".
    str_pad('DB TABLES', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->dbInfo->tablesFinalCount."\n".
    str_pad('DB ROWS', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->dbInfo->tablesRowCount."\n".
    str_pad('DB FILE SIZE', 13, '_', STR_PAD_RIGHT).':'.$GLOBALS['DUPX_AC']->dbInfo->tablesSizeOnDisk."\n".
    "********************************************************************************";
DUPX_Log::info($log);

DUPX_Log::info("PHP:\t\t".phpversion().' | SAPI: '.php_sapi_name());
DUPX_Log::info("PHP MEMORY:\t".DUPX_Log::varToString($GLOBALS['PHP_MEMORY_LIMIT']).' | SUHOSIN: '.DUPX_Log::varToString($GLOBALS['PHP_SUHOSIN_ON']));
DUPX_Log::info("SERVER:\t\t".DUPX_Log::varToString($_SERVER['SERVER_SOFTWARE']));
DUPX_Log::info("DOC ROOT:\t".DUPX_Log::varToString($root_path));
DUPX_Log::info("DOC ROOT 755:\t".DUPX_Log::varToString($GLOBALS['CHOWN_ROOT_PATH']));
DUPX_Log::info("LOG FILE 644:\t".DUPX_Log::varToString($GLOBALS['CHOWN_LOG_PATH']));
DUPX_Log::info("REQUEST URL:\t".DUPX_Log::varToString($GLOBALS['URL_PATH']));
DUPX_Log::info("SAFE MODE :\t".DUPX_Log::varToString($_POST['exe_safe_mode']));
DUPX_Log::info("CONFIG MODE :\t".DUPX_Log::varToString($_POST['config_mode']));

$log = "--------------------------------------\n";
$log .= "POST DATA\n";
$log .= "--------------------------------------\n";
$log .= print_r($POST_LOG, true);
DUPX_Log::info($log, 2);


$log = "--------------------------------------\n";
$log .= "PRE-EXTRACT-CHECKS\n";
$log .= "--------------------------------------";
DUPX_Log::info($log);
DUPX_ServerConfig::beforeExtractionSetup();


$log = "--------------------------------------\n";
$log .= "ARCHIVE SETUP\n";
$log .= "--------------------------------------\n";
$log .= "NAME:\t{$GLOBALS['FW_PACKAGE_NAME']}\n";
$log .= "SIZE:\t".DUPX_U::readableByteSize(@filesize($GLOBALS['FW_PACKAGE_PATH']));
 DUPX_Log::info($log."\n", DUPX_Log::LV_DEFAULT, true);


$target	 = $root_path;

$post_archive_engine = DUPX_U::sanitize_text_field($_POST['archive_engine']);
switch ($post_archive_engine) {

	//-----------------------
	//MANUAL EXTRACTION
	case 'manual':
		DUPX_Log::info("\n** PACKAGE EXTRACTION IS IN MANUAL MODE ** \n");
		break;

	//-----------------------
	//SHELL EXEC
	case 'shellexec_unzip':

		$shell_exec_path = DUPX_Server::get_unzip_filepath();
		DUPX_Log::info("ZIP:\tShell Exec Unzip");

		$command = escapeshellcmd($shell_exec_path)." -o -qq ".escapeshellarg($archive_path)." -d ".escapeshellarg($target)." 2>&1";
		if ($_POST['zip_filetime'] == 'original') {
			DUPX_Log::info("\nShell Exec Current does not support orginal file timestamp please use ZipArchive");
		}

		DUPX_Log::info(">>> Starting Shell-Exec Unzip:\nCommand: {$command}");
		$stderr = shell_exec($command);
		if ($stderr != '') {
			$zip_err_msg = ERR_SHELLEXEC_ZIPOPEN.": $stderr";
			$zip_err_msg .= "<br/><br/><b>To resolve error see <a href='https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-130-q' target='_blank'>https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-130-q</a></b>";
			DUPX_Log::error($zip_err_msg);
		}
		DUPX_Log::info("<<< Shell-Exec Unzip Complete.");

		break;

	//-----------------------
	//ZIP-ARCHIVE
	case 'ziparchive':
		DUPX_Log::info(">>> Starting ZipArchive Unzip");

		if (!class_exists('ZipArchive')) {
			DUPX_Log::info("ERROR: Stopping install process.  Trying to extract without ZipArchive module installed.  Please use the 'Manual Archive Extraction' mode to extract zip file.");
			DUPX_Log::error(ERR_ZIPARCHIVE);
		}

        if (($$extract_filenamesdupInstallerFolder = DUPX_U::findDupInstallerFolder($archive_path)) === false) {
            DUPX_Log::info("findDupInstallerFolder error; set no subfolder");
            // if not found set not subfolder
            $dupInstallerFolder = '';
        }
        if (!empty($dupInstallerFolder)) {
            DUPX_Log::info("ARCHIVE dup-installer SUBFOLDER:\"".$dupInstallerFolder."\"");
        } else {
            DUPX_Log::info("ARCHIVE dup-installer SUBFOLDER:\"".$dupInstallerFolder."\"", 2);
        }

        $dupInstallerZipPath = $dupInstallerFolder.'/dup-installer';

		$zip = new ZipArchive();

		if ($zip->open($archive_path) === TRUE) {
			$extract_filenames = array();
            DUPX_Handler::setMode(DUPX_Handler::MODE_VAR , false , false);

            for($i = 0; $i < $zip->numFiles; $i++) {
                $extract_filename = $zip->getNameIndex($i);
                
                // skip dup-installer folder. Alrady extracted in bootstrap
                if (strpos($extract_filename , $dupInstallerZipPath) === 0) {
                    continue;
                }

                // skip no dupInstallerFolder files
                if (!empty($dupInstallerFolder) && strpos($extract_filename , $dupInstallerFolder) !== 0) {
                    DUPX_Log::info("SKIP NOT DUB FOLDER: \"".$extract_filename."\"", 2);
                    continue;
				}

                try {
                    if (!$zip->extractTo($target , $extract_filename)) {
                        DUPX_Log::info("FILE EXTRACION ERROR: ".$extract_filename);
                        if (SnapLibUtilWp::isWpCore($extract_filename, SnapLibUtilWp::PATH_RELATIVE)) {
                            $shortMsg      = 'Can\'t extract wp core file: '.$extract_filename;
                            $finalShortMsg = 'Wp core file '.$extract_filename.' not extracted';
                            $errLevel      = DUPX_NOTICE_ITEM::CRITICAL;
                        } else {
                            $shortMsg      = 'Can\'t extract file: '.$extract_filename;
                            $finalShortMsg = 'File '.$extract_filename.' not extracted';
                            $errLevel      = DUPX_NOTICE_ITEM::SOFT_WARNING;
                        }
                        $longMsg = DUPX_Handler::getVarLogClean();

                        $nManager->addNextStepNotice(array(
                            'shortMsg' => $shortMsg,
                            'longMsg' => $longMsg,
                            'level' => $errLevel
                        ));
                        $nManager->addFinalReportNotice(array(
                            'shortMsg' => $finalShortMsg,
                            'longMsg' => $longMsg,
                            'level' => $errLevel,
                            'sections' => array('files'),
                        ));
                    } else {
                        DUPX_Log::info("DONE: ".$extract_filename,2);
                    }
                } catch (Exception $ex) {
                    DUPX_Log::info("FILE EXTRACION ERROR: {$extract_filename} | MSG:".$ex->getMessage());

                    if (SnapLibUtilWp::isWpCore($extract_filename, SnapLibUtilWp::PATH_RELATIVE)) {
                        $shortMsg      = 'Can\'t extract wp core file: '.$extract_filename;
                        $finalShortMsg = 'Wp core file '.$extract_filename.' not extracted';
                        $errLevel      = DUPX_NOTICE_ITEM::CRITICAL;
                    } else {
                        $shortMsg      = 'Can\'t extract file: '.$extract_filename;
                        $finalShortMsg = 'File '.$extract_filename.' not extracted';
                        $errLevel      = DUPX_NOTICE_ITEM::SOFT_WARNING;
                    }
                    $longMsg = $ex->getMessage();

                    $nManager->addNextStepNotice(array(
                        'shortMsg' => $shortMsg,
                        'longMsg' => $longMsg,
                        'level' => $errLevel
                    ));
                    $nManager->addFinalReportNotice(array(
                        'shortMsg' => $finalShortMsg,
                        'longMsg' => $longMsg,
                        'level' => $errLevel,
                        'sections' => array('files'),
                    ));
                }
			}

            if (!empty($dupInstallerFolder)) {
                DUPX_U::moveUpfromSubFolder($target.'/'.$dupInstallerFolder , true);
            }
            
			$log = print_r($zip, true);

			//FILE-TIMESTAMP
			if ($_POST['zip_filetime'] == 'original') {
				$log .= "File timestamp set to Original\n";
				for ($idx = 0; $s = $zip->statIndex($idx); $idx++) {
					touch($target.DIRECTORY_SEPARATOR.$s['name'], $s['mtime']);
				}
			} else {
				$now  = @date("Y-m-d H:i:s");
				$log .= "File timestamp set to Current: {$now}\n";
			}

            // set handler as default
            DUPX_Handler::setMode();

			$close_response = $zip->close();
			$log .= "<<< ZipArchive Unzip Complete: " . var_export($close_response, true);
			DUPX_Log::info($log);
		} else {
			$zip_err_msg = ERR_ZIPOPEN;
			$zip_err_msg .= "<br/><br/><b>To resolve error see <a href='https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-130-q' target='_blank'>https://snapcreek.com/duplicator/docs/faqs-tech/#faq-installer-130-q</a></b>";
			DUPX_Log::error($zip_err_msg);
		}

		break;

	//-----------------------
	//DUP-ARCHIVE
	case 'duparchive':
        DUPX_Log::info(">>> DupArchive Extraction Complete");

        if (isset($_POST['extra_data'])) {
			$extraData = $_POST['extra_data'];

			$log = "\n--------------------------------------\n";
			$log .= "DUPARCHIVE EXTRACTION STATUS\n";
			$log .= "--------------------------------------\n";

			$dawsStatus = json_decode($extraData);

			if ($dawsStatus === null) {
				$log .= "Can't decode the dawsStatus!\n";
				$log .= print_r(extraData, true);
			} else {
				$criticalPresent = false;

				if (count($dawsStatus->failures) > 0) {
					$log .= "Archive extracted with errors.\n";

					foreach ($dawsStatus->failures as $failure) {
						if ($failure->isCritical) {
							$log			 .= '(C) ';
							$criticalPresent = true;
						}
						$log .= "{$failure->description}\n";
					}
				} else {
					$log .= "Archive extracted with no errors.\n";
				}

				if ($criticalPresent) {
					$log .= "\n\nCritical Errors present so stopping install.\n";
					exit();
				}
			}

			DUPX_Log::info($log);
		} else {
			DUPX_LOG::info("DAWS STATUS: UNKNOWN since extra_data wasn't in post!");
		}

		break;
}


$log  = "--------------------------------------\n";
$log .= "POST-EXTACT-CHECKS\n";
$log .= "--------------------------------------";
DUPX_Log::info($log);

//===============================
//FILE PERMISSIONS
if ($_POST['set_file_perms'] || $_POST['set_dir_perms']) {

	// Skips past paths it can't read
	class IgnorantRecursiveDirectoryIterator extends RecursiveDirectoryIterator
	{
		function getChildren()
		{
			try {
				return new IgnorantRecursiveDirectoryIterator($this->getPathname());
			} catch (UnexpectedValueException $e) {
				return new RecursiveArrayIterator(array());
			}
		}
	}

	DUPX_Log::info("PERMISSION UPDATES:");
	DUPX_Log::info("    -DIRS:  '{$base_dir_perms_value}'");
	DUPX_Log::info("    -FILES: '{$base_file_perms_value}'");
	$set_file_perms		 = $_POST['set_file_perms'];
	$set_dir_perms		 = $_POST['set_dir_perms'];
	$set_file_mtime		 = ($_POST['zip_filetime'] == 'current');
	$file_perms_value	 = $_POST['file_perms_value'] ? $_POST['file_perms_value'] : 0755;
	$dir_perms_value	 = $_POST['dir_perms_value']  ? $_POST['dir_perms_value']  : 0644;

	$objects = new RecursiveIteratorIterator(new IgnorantRecursiveDirectoryIterator($root_path), RecursiveIteratorIterator::SELF_FIRST);

	foreach ($objects as $name => $object) {
		if ($set_file_perms && is_file($name)) {

			if (! @chmod($name, $file_perms_value)) {
				DUPX_Log::info("Permissions setting on file '{$name}' failed");
			}
		} else if ($set_dir_perms && is_dir($name)) {

			if (! @chmod($name, $dir_perms_value)) {
				DUPX_Log::info("Permissions setting on directory '{$name}' failed");
			}
		}
		if ($set_file_mtime) {
			@touch($name);
		}
	}
} else {
	DUPX_Log::info("\nPERMISSION UPDATES: None Applied");
}

DUPX_ServerConfig::afterExtractionSetup();
$nManager->saveNotices();

//FINAL RESULTS
$ajax1_sum	 = DUPX_U::elapsedTime(DUPX_U::getMicrotime(), $ajax1_start);
DUPX_Log::info("\nSTEP-1 COMPLETE @ " . @date('h:i:s') . " - RUNTIME: {$ajax1_sum}");

$JSON['pass'] = 1;
error_reporting($ajax1_error_level);
fclose($GLOBALS["LOG_FILE_HANDLE"]);
die(json_encode($JSON));
