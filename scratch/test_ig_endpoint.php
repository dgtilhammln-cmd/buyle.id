<?php
$token = 'EAAWwZCIQZAmwIBS8ZCY9OGEavn2ZBTHnP4MXwmyJ3R4XNuRO1iPeBDSAKa85sBfaIi0Bh0OEnXXZAMXsdDemLaq3rajeJsDTGf2jMnBO8EJXXxmfnSPl3kpvsznxIyViu949SFulzfU4FGrn8NE7KFS6V3TNV4BLCpxbIrpPtJ5Lt9hRHl5sLZArZAtOQJN3TI3hem1Gk9ZBWizulwZADEfIKDoSXluqqZClAk4gKT8Y43nGZCoIwHeK9itiZA43vGLOQZCmI3hQX2qcz8gs5uVBlxAAOcSwX6gZDZD';

$endpoints = [
    'https://graph.instagram.com/me?fields=id,username,account_type&access_token=' . $token,
    'https://graph.instagram.com/v19.0/me?fields=id,username&access_token=' . $token,
    'https://graph.facebook.com/v19.0/1667570271825926?fields=id,name&access_token=' . $token,
];

foreach ($endpoints as $url) {
    $ctx = stream_context_create(['http' => ['ignore_errors' => true]]);
    $res = file_get_contents($url, false, $ctx);
    echo $url . " => \n" . $res . "\n---\n";
}
