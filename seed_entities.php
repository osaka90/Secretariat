<?php
$senders = \App\Models\MinisterPublicRegisterIncoming::distinct()->pluck('sender')->filter();
foreach ($senders as $s) {
    \App\Models\Entity::firstOrCreate(['name' => $s]);
}

$corrs = \App\Models\MinisterPublicRegisterCorrespondence::distinct()->pluck('correspondent')->filter();
foreach ($corrs as $c) {
    \App\Models\Entity::firstOrCreate(['name' => $c]);
}

echo "Seeded entities successfully.";
