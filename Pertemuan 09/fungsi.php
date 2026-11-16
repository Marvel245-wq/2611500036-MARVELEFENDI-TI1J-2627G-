<?php
function bersihkan(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, "UTF-8");
}

function tebal(string $teks): string
{
    return "<strong>" . $teks . "</strong>";
}
