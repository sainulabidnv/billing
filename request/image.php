<?php
/**
 * Express Invoice & Stock Manager
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  E-mail: support@skyresoft.com
 *  Website: https://www.skyresoft.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://skyresoft.com/licenses/standard/
 * ***********************************************************************
 */
include('../includes/pfunctions.php');
/*defined settings - start*/
ini_set("memory_limit", "99M");
ini_set('post_max_size', '20M');
ini_set('max_execution_time', 600);
define('IMAGE_SMALL_DIR', '../images/');
define('IMAGE_SMALL_W', 150);
define('IMAGE_SMALL_H', 100);
define('IMAGE_MEDIUM_DIR', '../images/');
define('IMAGE_MEDIUM_SIZE', 250);
/*defined settings - end*/

if (isset($_FILES['image_upload_file'])) {
    $output['status'] = false;
    set_time_limit(0);
    $allowedImageType = array(
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/x-png");

    if ($_FILES['image_upload_file']["error"] > 0) {
        $output['error'] = "Error in File";
    } elseif (!in_array($_FILES['image_upload_file']["type"], $allowedImageType)) {
        $output['error'] = "You can only upload JPG, PNG and GIF file";
    } elseif (round($_FILES['image_upload_file']["size"] / 1024) > 4096) {
        $output['error'] = "You can upload file size up to 4 MB";
    } else {
        /*create directory with 777 permission if not exist - start*/
        createDir(IMAGE_SMALL_DIR);
        createDir(IMAGE_MEDIUM_DIR);
        /*create directory with 777 permission if not exist - end*/
        $path[0] = $_FILES['image_upload_file']['tmp_name'];
        $file = pathinfo($_FILES['image_upload_file']['name']);
        $fileType = $file["extension"];
        $desiredExt = 'jpg';
        $fileNameNew = "logo.$desiredExt";
        $path[1] = IMAGE_MEDIUM_DIR . $fileNameNew;
        $fileNameNew = "small_logo.$desiredExt";
        $path[2] = IMAGE_SMALL_DIR . $fileNameNew;

        if (createThumb($path[0], $path[1], $fileType, IMAGE_MEDIUM_SIZE,
            IMAGE_MEDIUM_SIZE, '')) {
            $output['status'] = true;
            $output['image_medium'] = $path[1];

        }
        if (createThumb($path[1], $path[2], "$desiredExt", IMAGE_SMALL_W, IMAGE_SMALL_H,
            '')) {
            $output['status'] = true;

            $output['image_small'] = $path[2];
        }
    }
    echo json_encode($output);
}
