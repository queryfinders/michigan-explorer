<?php
$html = file_get_contents('P:\xampp83\htdocs\michigan-explorer\resources\views\new_content\admin\hotels\edit.blade.php');
$lines = explode("\n", $html);
$balance = 0;
foreach ($lines as $i => $line) {
    if (strpos($line, 'id="hotelFormTabsContent"') !== false) {
        $balance = 1;
        echo "Line " . ($i+1) . " (hotelFormTabsContent): Balance = $balance\n";
    }
    
    if ($balance > 0) {
        $open = substr_count($line, '<div');
        $close = substr_count($line, '</div');
        $balance += ($open - $close);
        
        if ($open != $close) {
            echo "Line " . ($i+1) . ": +$open -$close (Balance: $balance)\n";
        }
        
        if ($balance <= 0) {
            echo "HOTEL FORMS TAB CONTENT CLOSED AT LINE " . ($i+1) . "!\n";
            break;
        }
    }
}
