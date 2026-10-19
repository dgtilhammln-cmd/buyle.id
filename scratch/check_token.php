<?php
$token = 'EAAWwZCIQZAmwIBS8ZCY9OGEavn2ZBTHnP4MXwmyJ3R4XNuRO1iPeBDSAKa85sBfaIi0Bh0OEnXXZAMXsdDemLaq3rajeJsDTGf2jMnBO8EJXXxmfnSPl3kpvsznxIyViu949SFulzfU4FGrn8NE7KFS6V3TNV4BLCpxbIrpPtJ5Lt9hRHl5sLZArZAtOQJN3TI3hem1Gk9ZBWizulwZADEfIKDoSXluqqZClAk4gKT8Y43nGZCoIwHeK9itiZA43vGLOQZCmI3hQX2qcz8gs5uVBlxAAOcSwX6gZDZD';

// 1. Check permissions
$url = "https://graph.facebook.com/v19.0/me/permissions?access_token=" . $token;
$res = @file_get_contents($url);
echo "=== PERMISSIONS ===\n" . $res . "\n";

// 2. Check accounts & instagram
$url2 = "https://graph.facebook.com/v19.0/me/accounts?fields=id,name,access_token,instagram_business_account{id,username}&access_token=" . $token;
$res2 = @file_get_contents($url2);
echo "=== ACCOUNTS ===\n" . $res2 . "\n";
