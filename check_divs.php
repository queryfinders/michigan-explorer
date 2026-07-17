<?php
$html = file_get_contents('P:\xampp83\htdocs\michigan-explorer\resources\views\new_content\admin\hotels\edit.blade.php');
preg_match('/id="details-pane".*?(?=<!-- Tab 3)/s', $html, $matches);
$text = $matches[0];
$open = substr_count($text, '<div');
$close = substr_count($text, '</div');
echo "details-pane: $open open, $close close\n";

preg_match('/id="basic-pane".*?(?=<!-- Tab 2)/s', $html, $matches);
$text = $matches[0];
$open = substr_count($text, '<div');
$close = substr_count($text, '</div');
echo "basic-pane: $open open, $close close\n";
