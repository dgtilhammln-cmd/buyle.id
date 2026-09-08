<?php
/**
 * Patch bio themes 2-5 to add click tracking data attributes and JS.
 */

$base = 'c:/Users/dgtil/Downloads/PENTING/HVM Digital/buyle.id ex alaatrumah/resources/views/bio';
$themes = ['theme2', 'theme3', 'theme4', 'theme5'];

$trackJs = <<<'JSEND'

            // Bio Link Click Tracker
            document.querySelectorAll('.bio-track-link').forEach(function(el) {
                el.addEventListener('click', function() {
                    var urlParams = new URLSearchParams(window.location.search);
                    var payload = {
                        block_id:    this.dataset.bioBlock || null,
                        creator_id:  this.dataset.bioCreator || null,
                        url:         this.href || null,
                        title:       this.dataset.title || null,
                        utm_source:  urlParams.get('utm_source') || null,
                        utm_medium:  urlParams.get('utm_medium') || null,
                        utm_campaign:urlParams.get('utm_campaign') || null,
                        utm_content: urlParams.get('utm_content') || null,
                    };
                    navigator.sendBeacon(window.location.origin + '/track/bio-click', new Blob([JSON.stringify(payload)], {type:'application/json'}));
                }, { passive: true });
            });
JSEND;

foreach ($themes as $t) {
    $f = $base . '/' . $t . '.blade.php';
    if (!file_exists($f)) { echo "SKIP: $f\n"; continue; }

    $c = file_get_contents($f);
    $changed = false;

    // === 1. Patch anchors ===
    $find_replace = [
        // TikTok (themes 2-4)
        'class="video-card tt-fetch search-item" data-title="TikTok Video" data-url="{{ $b->url }}">'
            => 'class="video-card tt-fetch search-item bio-track-link" data-title="TikTok Video" data-url="{{ $b->url }}" data-bio-block="{{ $b->id }}" data-bio-creator="{{ $profile->id }}">',
        // TikTok (theme5 uses different class)
        'class="tiktok-card-item tt-fetch search-item" data-title="TikTok Video" data-url="{{ $b->url }}">'
            => 'class="tiktok-card-item tt-fetch search-item bio-track-link" data-title="TikTok Video" data-url="{{ $b->url }}" data-bio-block="{{ $b->id }}" data-bio-creator="{{ $profile->id }}">',
        // glass-btn link blocks
        'class="glass-btn fade-up search-item" data-title="{{ $block->title }}"'
            => 'class="glass-btn fade-up search-item bio-track-link" data-title="{{ $block->title }}" data-bio-block="{{ $block->id }}" data-bio-creator="{{ $profile->id }}"',
        // affiliate/shopee prod-card
        'class="prod-card search-item" data-title="{{ $block->title }}">'
            => 'class="prod-card search-item bio-track-link" data-title="{{ $block->title }}" data-bio-block="{{ $block->id }}" data-bio-creator="{{ $profile->id }}">',
        // custom_product prod-card (has price in data-title - old style)
        "class=\"prod-card search-item\" data-title=\"{{ \$block->title }} {{ \$price }} {{ \$config['wa'] ?? '' }}\">"
            => 'class="prod-card search-item bio-track-link" data-title="{{ $block->title }}" data-bio-block="{{ $block->id }}" data-bio-creator="{{ $profile->id }}">',
        // buyle_product prod-card
        'class="prod-card fade-up search-item" data-title="{{ $prod->name }}"'
            => 'class="prod-card fade-up search-item bio-track-link" data-title="{{ $prod->name }}" data-bio-block="{{ $block->id }}" data-bio-creator="{{ $profile->id }}"',
    ];

    foreach ($find_replace as $find => $replace) {
        if (strpos($c, $find) !== false) {
            $c = str_replace($find, $replace, $c);
            $changed = true;
        }
    }

    // === 2. Inject JS tracker before closing of DOMContentLoaded ===
    if (strpos($c, 'Bio Link Click Tracker') === false) {
        // Find ending of the TikTok DOMContentLoaded block
        // Pattern: "    });\n    </script>" (2 closings for forEach + DOMContentLoaded)
        $patterns = [
            "\r\n            });\r\n        });\r\n    </script>",
            "\n            });\n        });\n    </script>",
        ];
        foreach ($patterns as $p) {
            if (strpos($c, $p) !== false) {
                $newEnd = $p[0] === "\r"
                    ? "\r\n            });\r\n" . $trackJs . "\r\n        });\r\n    </script>"
                    : "\n            });\n" . $trackJs . "\n        });\n    </script>";
                $c = str_replace($p, $newEnd, $c);
                $changed = true;
                echo "JS injected ($p[0] === \\r ? CRLF : LF): $t\n";
                break;
            }
        }
        if (strpos($c, 'Bio Link Click Tracker') === false) {
            echo "WARNING: JS not injected in $t\n";
        }
    } else {
        echo "ALREADY HAS TRACKER: $t\n";
    }

    if ($changed) {
        file_put_contents($f, $c);
        echo "SAVED: $t\n";
    } else {
        echo "NO CHANGES: $t\n";
    }
}

echo "\nAll done.\n";
