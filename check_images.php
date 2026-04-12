<?php
$content = file_get_contents('app/Http/Controllers/PresenteController.php');
preg_match_all("/'imagem' => '(.*?)'/", $content, $matches);
foreach ($matches[1] as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($code == 404 || $code == 0) {
        echo "FAILED: $url\n";
    }
    curl_close($ch);
}
echo "DONE\n";
