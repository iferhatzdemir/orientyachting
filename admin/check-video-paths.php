<?php
header('Content-Type: application/json');
$paths = array(
    'yachts_videos_dir' => realpath('../images/yachts/videos'),
    'resimler_videos_dir' => realpath('../images/resimler/videos'),
    'files_in_yachts_videos' => glob('../images/yachts/videos/*.*'),
    'files_in_resimler_videos' => glob('../images/resimler/videos/*.*')
);
echo json_encode($paths);
?>