<?php
$token = 'EAAWwZCIQZAmwIBS8ZCY9OGEavn2ZBTHnP4MXwmyJ3R4XNuRO1iPeBDSAKa85sBfaIi0Bh0OEnXXZAMXsdDemLaq3rajeJsDTGf2jMnBO8EJXXxmfnSPl3kpvsznxIyViu949SFulzfU4FGrn8NE7KFS6V3TNV4BLCpxbIrpPtJ5Lt9hRHl5sLZArZAtOQJN3TI3hem1Gk9ZBWizulwZADEfIKDoSXluqqZClAk4gKT8Y43nGZCoIwHeK9itiZA43vGLOQZCmI3hQX2qcz8gs5uVBlxAAOcSwX6gZDZD';

$endpoints = [
    'me?fields=id,name',
    'me/accounts',
    'me?fields=id,name,accounts',
    'me?fields=id,name,permissions',
    'debug_token?input_token=' . $token,
];

foreach ($endpoints as $ep) {
    $url = 'https://graph.facebook.com/v19.0/' . $ep . (str_contains($ep, '?') ? '&' : '?') . 'access_token=' . $token;
    $ctx = stream_context_create(['http' => ['ignore_errors' => true]]);
    $res = file_get_contents($url, false, $ctx);
    echo $ep . " => \n" . $res . "\n---\n";
}
