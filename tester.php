<?php
    require 'FilesAPI.php';
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    set_time_limit(0);

    $fileManager=new FilesAPI();
    $fileManager->getDirectoryUrl(0);
    $fileManager->getDirectoryFiles();
    $left_boarder_date=strtotime('2021-11-20');
    $fileManager->getFilesLinks($left_boarder_date);

    $fileManager->loadFiles();
    $fileManager->getLoadedFilesCount();
