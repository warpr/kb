<?php

function str_starts_with ( $haystack, $needle ) {
    return strpos( $haystack , $needle ) === 0;
}

$thumb = [ null, null, null, '⌥', '⌘', "⎵\t\tLower", null, "⏎\t\tRaise", "⌘", '⌥', null, null, null ];

$dvorak = [
    [ 'TAB', "'", ",", ".", "p", "y", null, "f", "g", "c", "r", "l", "ESC" ],
    [ "\t\tCTRL", 'a', 'o', 'e', 'u', 'i', null, 'd', 'h', 't', 'n', 's', "-\t_" ],
    [ "&nbsp;(\t\t⇧", ";", 'q', 'j', 'k', 'x', null, 'b', 'm', 'w', 'v', 'z', "&nbsp;)\t\t⇧" ],
    $thumb,
];

$lower = [
    [ "<", "1", "2", "3", "4", "5", null, "6", "7", "8", "9", "0", '>' ],
    [ '{', '!', '@', '#', '$', '%', null, '?', '/', '=', '\\', '|', '}' ],
    [ '[', '', ':', '`', '~', '"',  null, '^', '&', '*', '+', '', ']', ],
    $thumb,
];

$raise = [
    [ '', '', '', '', '', '', null, 'EMACS. C-x [', 'EMACS. M-b', '↑', 'EMACS. M-f', 'Page Up', 'Home' ],
    [ '', '', '', '', '', '', null, 'EMACS. C-x ]', '←', '↓', '→', 'Page Down', 'End' ],
    [ '', '', '', '', '', '', null, '', 'MACRO. Moom', 'DEL', '⌫', '', '' ],
    $thumb,
];

$accents = [
    [ 'ò', 'æ', '≤', '≥', 'π', '\\', null, 'ƒ', '©', 'ç', '®', '¬', ' ' ],
    [ ' ', 'å', 'ø', 'ó', 'ö', 'ô', null,  '∂', '˙', '†', 'ñ', 'ß', '–' ],
    [ ' ', '…', 'œ', '∆', '˚', '≈', null,  '∫', 'µ', '∑', '√', 'Ω', ' ' ],
    $thumb,
];

$layers = [
    "dovark" => $dvorak,
    "lower" => $lower,
    "raise" => $raise,
    "accents (macos)" => $accents,
];

function render_key($key) {
    if ($key === null) {
        echo "<key-placeholder> </key-placeholder>";
        return;
    }

    $parts = explode("\t",$key);

    echo "<key-cap>";

    if (preg_match("/^EMACS\.(.*)$/", $parts[0], $matches)) {
        echo "<key-macro>" . trim($matches[1]) . "</key-macro>";
        echo "<key-emacs>emacs</key-emacs>";
    } else if (preg_match("/^MACRO\.(.*)$/", $parts[0], $matches)) {
        echo "<key-macro>" . trim($matches[1]) . "</key-macro>";
        echo "<key-emacs>macro</key-emacs>";
    } else {
        echo "<key-tap>" . $parts[0] . "</key-tap>";
    }

    if (!empty($parts[1])) {
        echo "<key-shifted>" . $parts[1] . "</key-shifted>";
    }
    if (!empty($parts[2])) {
        echo "<key-hold>" . $parts[2] . "</key-hold>";
    }
    echo "</key-cap>\n";
}

function render_layer($name, $data) {
    echo "<layer-label><h1>$name</h1></layer-label>";
    foreach ($data as $row) {
        echo "<key-row>";
        foreach ($row as $key) {
            render_key($key);
        }
        echo "</key-row>";
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Keyboard Layout</title>
        <style>
            <?php require_once('style.css'); ?>
        </style>
    </head>
    <body>
   <?php
        foreach ($layers as $name => $data) {
            render_layer($name, $data);
            echo "<br/><br/>";
        }
   ?>
    </body>
</html>
