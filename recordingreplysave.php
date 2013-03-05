<?php
session_start();
$output_dir = "site_files/user_sms/";
//chmod($output_dir, 777);
$bleepFileName = "music_".time();
$wavFile = $bleepFileName . ".wav";
$mp3File = $bleepFileName . ".mp3";
$fp = fopen( $output_dir.$wavFile, 'wb' );
fwrite( $fp, $GLOBALS[ 'HTTP_RAW_POST_DATA' ] );
fclose( $fp );
$comm = "/usr/local/bin/ffmpeg -t 30 -i ".$output_dir.$wavFile." -acodec libmp3lame -ab 192k ".$output_dir.$mp3File;
exec($comm);
unlink($output_dir.$wavFile);
$_SESSION["session_recording_reply"] = $mp3File;
?>