<?php
/**
 * img
 */
parse_str($options);
$default_width = isset($w) ? $w : 300;

$output = &$input;
preg_match_all('/<img[^>]+>/i', $input, $images);
foreach ($images[0] as $image) {
    $img = array();
    
    preg_match_all('#([a-zA-Z]+)="(.*?)"#', $image, $matches);
    for ($i = 0, $j = count($matches[0]); $i < $j; $i++) {
        $img[$matches[1][$i]] = $matches[2][$i];
    }
    
    $class = isset($img['class']) ? explode(' ', $img['class']) : array();
    if (!in_array('noresize', $class)) {
        $tag = '<span style="color: red;">Image error!</span>';
        
        $file_path = $modx->getOption('base_path').$img['src'];
        if (file_exists($file_path)) {
            $class[] = 'resized';
            $img['class'] = implode(' ', $class);
            
            list($original_width, $original_height) = getimagesize($img['src']);
            $ratio = $original_width / $original_height;
            if (empty($img['width'])) $img['width'] = $original_width;
            $img['width'] = $img['width'] > $default_width ? $default_width : $img['width'];
            if (empty($img['height'])) $img['height'] = round($img['width'] / $ratio);
            if (!isset($img['title'])) $img['title'] = '';
            
            ksort($img);
            
            if (isset($tpl)) {
                $tag = $modx->getChunk($tpl, $img);
            } else {
                $tag = '<img ';
                foreach ($img as $key => $val) {
                    $tag .= $key.'="'.$val.'" ';
                }
                $tag .= '/>';
            }
        }
        $output = str_replace($image, $tag, $output);
    }
}
return $output;