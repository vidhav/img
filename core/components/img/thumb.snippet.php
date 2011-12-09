<?php
$src = '';
$img_start = strpos($input, '<img ', 0);
if ($img_start !== false) {
    $src_start = strpos($input, ' src="', $img_start) + 6;
    $src_length = strpos($input, '"', $src_start) - $src_start;
    $src = substr($input, $src_start, $src_length);
}
return empty($src) ? 'asset/images/dummy.jpg' : $src;