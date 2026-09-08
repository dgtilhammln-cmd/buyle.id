<?php
$dir = 'c:/Users/dgtil/Downloads/PENTING/HVM Digital/buyle.id ex alaatrumah/resources/views/bio';
$themes = ['theme1','theme2','theme3','theme4','theme5'];
foreach ($themes as $t) {
    $f = $dir . '/' . $t . '.blade.php';
    $c = file_get_contents($f);
    $new = str_replace(
        "window.location.origin + '/track/bio-click'",
        "window.location.origin + '/track-bio'",
        $c
    );
    // Also fix the Blade url() version if it exists
    $new = str_replace(
        "{{ url('/track/bio-click') }}",
        "{{ url('/track-bio') }}",
        $new
    );
    if ($c !== $new) {
        file_put_contents($f, $new);
        echo "UPDATED: $t\n";
    } else {
        echo "NO CHANGE: $t\n";
    }
}
echo "Done.\n";
