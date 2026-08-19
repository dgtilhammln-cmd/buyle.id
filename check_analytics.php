<?php
$db = new PDO('sqlite:database/database.sqlite');
// Check table schema
$r = $db->query("PRAGMA table_info(analytics_events)");
foreach ($r as $row) { echo $row['name'] . ' (' . $row['type'] . ')' . PHP_EOL; }
echo PHP_EOL;
// Check latest records
$r2 = $db->query("SELECT * FROM analytics_events ORDER BY created_at DESC LIMIT 3");
foreach ($r2 as $row) { print_r($row); break; }

foreach ($r as $row) {
    echo $row['type'] . ': ' . $row['c'] . ' | last=' . $row['last'] . PHP_EOL;
}
echo PHP_EOL;
$r2 = $db->query("SELECT MIN(created_at) as first, MAX(created_at) as last FROM analytics_events WHERE type='pageview'");
$row2 = $r2->fetch();
echo 'Pageview range: ' . $row2['first'] . ' to ' . $row2['last'] . PHP_EOL;
echo 'Today: ' . date('Y-m-d') . PHP_EOL;
echo '30 days ago: ' . date('Y-m-d', strtotime('-29 days')) . PHP_EOL;

$r3 = $db->query("SELECT COUNT(*) as c FROM analytics_events WHERE type='pageview' AND created_at >= date('now', '-29 days')");
echo 'Last 30d pageviews: ' . $r3->fetch()['c'] . PHP_EOL;
