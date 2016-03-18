<?php
date_default_timezone_set('PRC');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: OPTIONS , POST");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit; // finish preflight CORS requests here
}

// 5 minutes execution time
@set_time_limit(5 * 60);

#$dirSor = DIRECTORY_SEPARATOR;
$dirSor = '/';

// Settings
// $targetDir = ini_get("upload_tmp_dir") . $dirSor . "plupload";
$targetDir = 'upload_chunk';
$uploadDir = 'upload_temp';
$uploadPath = $uploadDir .$dirSor. date('Y/m-d') . $dirSor;


$cleanupTargetDir = true; // Remove old files
$maxFileAge = 5 * 3600; // Temp file age in seconds

// Create target dir
if (!file_exists($targetDir))
	@mkdir($targetDir , 0777 , true);

// Create target dir
if (!file_exists($uploadPath))
    @mkdir($uploadPath , 0777 , true);

// Get a file name
$fileName = $fileSuffix = '';
if (isset($_REQUEST["name"])) {
    $fileName = $_REQUEST["name"];
} elseif (!empty($_FILES)) {
    $fileName = $_FILES["file"]["name"];
} else {
    $fileName = uniqid("file_");
}

//验证文件类型
$fileTypes = array('jpg','jpeg','gif','png','bmp','zip','rar','txt','doc','docx','ppt','els','xls','xlsx');
$fileParts = pathinfo($fileName);
if (empty($fileName) || empty($fileParts['extension']) || !in_array(strtolower($fileParts['extension']) , $fileTypes))
	 exit('{"code": 105, "message": "The file type error."}');

$fileSuffix = $fileParts['extension'];
$filePath = $targetDir . $dirSor . $fileName;

// Chunking might be enabled
$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;


// Remove old temp files
if ($cleanupTargetDir)
{
    if (!is_dir($targetDir) || !$dir = opendir($targetDir))
        exit('{"code": 100, "message": "Failed to open temp directory."}');

    while (($file = readdir($dir)) !== false)
    {
        $tmpfilePath = $targetDir . $dirSor . $file;
        // If temp file is current file proceed to the next
        if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp")
            continue;

        // Remove temp file if it is older than the max age and is not the current file
        if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge))
            @unlink($tmpfilePath);
    }
    closedir($dir);
}

// Open temp file
if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb"))
    exit('{"code": 102, "message": "Failed to open output stream."}');

if (!empty($_FILES))
{
    if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"]))
        exit('{"code": 103, "message": "Failed to move uploaded file."}');

    // Read binary input stream and append it to temp file
    if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb"))
        exit('{"code": 101, "message": "Failed to open input stream."}');
} else {
    if (!$in = @fopen("php://input", "rb"))
        exit('{"code": 101, "message": "Failed to open input stream."}');
}

while ($buff = fread($in, 4096))
    fwrite($out, $buff);
@fclose($out);
@fclose($in);

rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

$index = 0;
$done = true;
for( $index = 0; $index < $chunks; $index++ )
{
    if ( !file_exists("{$filePath}_{$index}.part") )
    {
        $done = false;
        break;
    }
}

if ($done)
{
	$uploadPath .= mt_rand().microtime(true) .'.'. $fileSuffix;
    if (!$out = @fopen($uploadPath, "wb"))
        exit('{"code": 106, "message": "Failed to open output stream."}');

    if ( flock($out, LOCK_EX) )
    {
        for( $index = 0; $index < $chunks; $index++ )
        {
            if (!$in = @fopen("{$filePath}_{$index}.part", "rb"))
                break;

            while ($buff = fread($in, 4096))
                fwrite($out, $buff);

            @fclose($in);
            @unlink("{$filePath}_{$index}.part");
        }
        flock($out, LOCK_UN);
    }
    @fclose($out);
    exit('{"code":0,"src":"'.$dirSor.$uploadPath.'"}');
}

// Return Success JSON-RPC response
exit('{"code": -1, "message": "unknown"}');