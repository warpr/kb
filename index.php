<?php

function str_starts_with($haystack, $needle)
{
    return strpos($haystack, $needle) === 0;
}

$thumb = [null, null, null, "\t\t⌥", "\t\t⌘", "⎵\t\tLower", null, "⏎\t\tRaise", "\t\t⌘", "\t\t⌥", null, null, null];

$dvorak = [
    ['TAB', "'\t\"", ",\t<", ".\t>", 'P', 'Y', null, 'F', 'G', 'C', 'R', 'L', 'ESC'],
    ["\t\tCTRL", 'A', 'O', 'E', 'U', 'I', null, 'D', 'H', 'T', 'N', 'S', "-\t_"],
    ["&nbsp;(\t\t⇧", ";\t:", 'Q', 'J', 'K', 'X', null, 'B', 'M', 'W', 'V', 'Z', "&nbsp;)\t\t⇧"],
    $thumb,
];

$lower = [
    ['', 'Page Up', 'Word Left', '↑', 'Word Right', 'Home', null, 'DIM. *', '7', '8', '9', 'DIM. -', 'DIM. ⌫'],
    ['', 'Page Down', '←', '↓', '→', 'End', null, 'DIM. /', '4', '5', '6', 'DIM. +', 'DIM. ⏎'],
    ['', '', 'DEL', 'MACRO. ⌘⇧3', 'MACRO. ⌘⇧4', 'MACRO. ⌘⇧5', null, 'DIM. .', '1', '2', '3', 'DIM. =', ''],
    [null, null, null, "\t\t⌥", "\t\t⌘", "⎵\t\tLower", null, '0', "\t\t⌘", "\t\t⌥", null, null, null],
];

$raise = [
    ['DIM. `', '!', '@', '#', '%', '', null, '^', '&', '*', '+', '', 'DIM. ⌫'],
    ['{', '', '`', '~', '$', '', null, '?', '/', '=', '\\', '|', '}'],
    ['[', '', '', '', '', '', null, '', 'MOOM. ⎈⌘M', '', '', '', ']'],
    [null, null, null, "\t\t⌥", "\t\t⌘", "\t\tAdjust", null, "⏎\t\tRaise", "\t\t⌘", "\t\t⌥", null, null, null],
];

$accents = [
    ['ù', 'DIM. æ', 'DIM. ≤', 'DIM. ≥', 'DIM. π', 'DIM. \\', null, 'DIM. ƒ', 'DIM. ©', 'DIM. ç', 'DIM. ®', 'DIM. ¬', ''],
    ['DIM.  ', 'DIM. å', 'DIM. ø', 'é', 'ü', 'î', null, 'DIM. ∂', 'DIM. ˙', 'DIM. †', 'ñ', 'DIM. ß', ''],
    ['DIM.  ', 'DIM. …', 'DIM. œ', 'DIM. ∆', 'DIM. ˚', 'DIM. ≈', null, 'DIM. ∫', 'DIM. µ', 'DIM. ∑', 'DIM. √', 'DIM. Ω', 'DIM.  '],
    $thumb,
];

$adjust = [
    ['', 'F1', 'F2', 'F3', 'F4', '', null, 'INS', 'Print Scrn', 'Scroll Lock', "Pause\t\tBreak", '', ''],
    ['', 'F5', 'F6', 'F7', 'F8', '', null, '', '', '', '', '', ''],
    ['', 'F9', 'F10', 'F11', 'F12', '', null, '', '', '', '', '', ''],
    $thumb,
];

$layers = [
    'Dvorak' => $dvorak,
    'Lower' => $lower,
    'Raise' => $raise,
    'MacOS ⌥' => $accents,
    'Adjust' => $adjust,
];

function render_key($key)
{
    if ($key === null) {
        echo '<key-placeholder> </key-placeholder>';
        return;
    }

    $parts = explode("\t", $key);

    echo '<key-cap>';

    if (preg_match("/^DIM\.(.*)$/", $parts[0], $matches)) {
        echo '<key-tap class="big dim">' . trim($matches[1]) . '</key-tap>';
    } elseif (preg_match("/^(([A-Z]+)\. )(.*)$/", $parts[0], $matches)) {
        $grp = strtolower(trim($matches[2]));
        echo "<key-macro class='" . $grp . "'>" . trim($matches[3]) . '</key-macro>';
        echo "<key-macro-desc class='" . $grp . "'>" . $grp . '</key-macro-desc>';
    } elseif (
        count($parts) == 1 &&
        (mb_strlen(trim($parts[0])) == 1 || $parts[0] == 'é' || $parts[0] == 'ü' || $parts[0] == 'î' || $parts[0] == 'ù')
    ) {
        echo '<key-tap class="big">' . trim($parts[0]) . '</key-tap>';
    } elseif (count($parts) == 2 && mb_strlen(trim($parts[0])) == 1 && mb_strlen(trim($parts[1])) == 1) {
        echo '<key-tap class="big-unshifted">' . trim($parts[0]) . '</key-tap>';
    } else {
        echo '<key-tap>' . $parts[0] . '</key-tap>';
    }

    if (!empty($parts[1])) {
        echo '<key-shifted>' . $parts[1] . '</key-shifted>';
    }
    if (!empty($parts[2])) {
        echo '<key-hold>' . $parts[2] . '</key-hold>';
    }
    echo "</key-cap>\n";
}

function render_layer($name, $data)
{
    echo '<layer-box>';
    foreach ($data as $row) {
        echo '<key-row>';
        foreach ($row as $key) {
            render_key($key);
        }
        echo '</key-row>';
    }
    echo "<layer-label><h1>$name</h1></layer-label>";
    echo '</layer-box>';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Keyboard Layout</title>
        <style>
            <?php require_once 'style.css'; ?>
        </style>
    </head>
    <body>
   <?php foreach ($layers as $name => $data) {
       render_layer($name, $data);
   } ?>
    </body>
</html>
