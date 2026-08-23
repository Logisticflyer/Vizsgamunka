<?php
// seed_templates.php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Console alkalmazás inicializálása
$config = file_exists(__DIR__ . '/config/console.php') 
    ? require __DIR__ . '/config/console.php' 
    : require __DIR__ . '/config/web.php';

(new yii\console\Application($config));

$db = Yii::$app->db;

echo "🚀 2000+ 3D Sablon Adatbázis Injektálás Indítása...\n";

// Előző adatok törlése a tiszta újratöltéshez
$db->createCommand('TRUNCATE TABLE templates_3d')->execute();

$rows = [];

// =========================================================================
// 1. KARIMÁK & CSŐVEZETÉKEK (~200 elem)
// =========================================================================
$dnSizes = [15, 20, 25, 32, 40, 50, 65, 80, 100, 125, 150, 200, 250, 300, 350, 400, 450, 500];
$pnPressures = ['PN10', 'PN16', 'PN25', 'PN40'];

foreach ($dnSizes as $dn) {
    foreach ($pnPressures as $pn) {
        $rows[] = [
            'id' => "flange_dn{$dn}_{$pn}",
            'category' => '1. Karimák & Csővezetékek',
            'name' => "Síkkarima DN{$dn} {$pn} (DIN 2576 / EN 1092-1)",
            'shape' => 'disc',
            'base_cost' => 15000 + ($dn * 250),
            'h' => 20 + (int)round($dn * 0.1),
            'w' => 80 + ($dn * 2),
            'l' => 80 + ($dn * 2)
        ];
    }
    
    $rows[] = [
        'id' => "pipe_dn{$dn}_1000",
        'category' => '1. Karimák & Csővezetékek',
        'name' => "Acél Csőidom DN{$dn} (Hossz: 1000mm)",
        'shape' => 'cylinder_horiz',
        'base_cost' => 22000 + ($dn * 180),
        'h' => $dn + 20,
        'w' => $dn + 20,
        'l' => 1000
    ];
}

// =========================================================================
// 2. CNC ESZTERGÁLT TENGELYEK (~250 elem)
// =========================================================================
for ($dia = 10; $dia <= 200; $dia += 10) {
    for ($length = 200; $length <= 2000; $length += 200) {
        $rows[] = [
            'id' => "shaft_d{$dia}_l{$length}",
            'category' => '2. CNC Esztergált Tengelyek',
            'name' => "Köszörült Precíziós Tengely Ø{$dia}mm x {$length}mm",
            'shape' => 'shaft',
            'base_cost' => 18000 + ($dia * 400) + ($length * 30),
            'h' => $dia,
            'w' => $dia,
            'l' => $length
        ];
    }
}

// =========================================================================
// 3. GARATOK & ADAGOLÓK (~100 elem)
// =========================================================================
for ($vol = 100; $vol <= 5000; $vol += 50) {
    $height = (int)round(800 + sqrt($vol) * 20);
    $width = (int)round(600 + sqrt($vol) * 15);
    
    $rows[] = [
        'id' => "hopper_v{$vol}",
        'category' => '3. Garatok & Adagolók',
        'name' => "Betöltő Garat {$vol}L (Rozsdamentes KO33)",
        'shape' => 'hopper',
        'base_cost' => 120000 + ($vol * 180),
        'h' => $height,
        'w' => $width,
        'l' => $width
    ];
}

// =========================================================================
// 4. NYOMÁSTARTÓ TARTÁLYOK (~100 elem)
// =========================================================================
for ($vol = 100; $vol <= 5000; $vol += 50) {
    $height = (int)round(1200 + sqrt($vol) * 20);
    $width = (int)round(600 + sqrt($vol) * 15);

    $rows[] = [
        'id' => "tank_v{$vol}",
        'category' => '4. Nyomástartó Tartályok',
        'name' => "Álló Process Tartály {$vol} Liters (PED Minősített)",
        'shape' => 'tank',
        'base_cost' => 250000 + ($vol * 320),
        'h' => $height,
        'w' => $width,
        'l' => $width
    ];
}

// =========================================================================
// 5. ZÁRTSZELVÉNY GÉPVÁZAK (~250 elem)
// =========================================================================
$profiles = [40, 50, 60, 80, 100, 120, 150, 200];
foreach ($profiles as $prof) {
    for ($w = 500; $w <= 2000; $w += 300) {
        for ($l = 1000; $l <= 3000; $l += 400) {
            $rows[] = [
                'id' => "frame_p{$prof}_{$w}x{$l}",
                'category' => '5. Zártszelvény Gépvázak',
                'name' => "Moduláris Gépváz {$prof}x{$prof}mm Zártszelvényből ({$w}x{$l}mm)",
                'shape' => 'frame',
                'base_cost' => 150000 + ($prof * 500) + ($w * 50) + ($l * 40),
                'h' => 1200,
                'w' => $w,
                'l' => $l
            ];
        }
    }
}

// =========================================================================
// 🌟 6. ÚJ: LINEÁRIS TECHNIKA & CNC VEZETÉKEK (~300 elem)
// =========================================================================
$railTypes = ['HGR15', 'HGR20', 'HGR25', 'HGR30', 'HGR35', 'HGR45', 'MGN9', 'MGN12', 'MGN15'];
for ($l = 200; $l <= 3000; $l += 100) {
    foreach ($railTypes as $type) {
        if ($l % 200 == 0) { // Szelektált hosszúságok
            $width = (int)substr($type, -2) ?: 15;
            $rows[] = [
                'id' => "linear_rail_{$type}_{$l}",
                'category' => '6. Lineáris Technika & Vezetékek',
                'name' => "Lineáris Sín {$type} Precíziós Vezeték (Hossz: {$l}mm)",
                'shape' => 'plate',
                'base_cost' => 25000 + ($l * 45) + ($width * 300),
                'h' => $width + 5,
                'w' => $width * 2,
                'l' => $l
            ];
        }
    }
}

$ballScrews = ['SFU1204', 'SFU1605', 'SFU2005', 'SFU2510', 'SFU3210'];
for ($l = 300; $l <= 2000; $l += 100) {
    foreach ($ballScrews as $sfu) {
        $dia = (int)substr($sfu, 3, 2);
        $rows[] = [
            'id' => "ballscrew_{$sfu}_{$l}",
            'category' => '6. Lineáris Technika & Vezetékek',
            'name' => "Megmunkált Golyósorsó Tengely {$sfu} (Hossz: {$l}mm)",
            'shape' => 'shaft',
            'base_cost' => 35000 + ($l * 55) + ($dia * 400),
            'h' => $dia,
            'w' => $dia,
            'l' => $l
        ];
    }
}

// =========================================================================
// 🌟 7. ÚJ: LÉZERVÁGOTT LEMEZALKATRÉSZEK & KONZOLOK (~300 elem)
// =========================================================================
$thicknesses = [2, 3, 4, 5, 6, 8, 10, 12, 15, 20];
for ($w = 100; $w <= 1000; $w += 100) {
    for ($l = 200; $l <= 1200; $l += 100) {
        foreach ($thicknesses as $t) {
            if (($w + $l) % 300 == 0) {
                $rows[] = [
                    'id' => "sheet_plate_t{$t}_{$w}x{$l}",
                    'category' => '7. Lézervágott Lemezalkatrészek',
                    'name' => "CNC Lézervágott Acél Alaplemez {$t}mm ({$w}x{$l}mm)",
                    'shape' => 'plate',
                    'base_cost' => 12000 + ($w * $l * $t * 0.08),
                    'h' => $t,
                    'w' => $w,
                    'l' => $l
                ];

                $rows[] = [
                    'id' => "sheet_bracket_t{$t}_{$w}x{$l}",
                    'category' => '7. Lézervágott Lemezalkatrészek',
                    'name' => "Hajlított L-Konzol Tartóelem {$t}mm ({$w}x{$l}mm)",
                    'shape' => 'block',
                    'base_cost' => 18000 + ($w * $l * $t * 0.1),
                    'h' => (int)($w / 2),
                    'w' => $w,
                    'l' => $l
                ];
            }
        }
    }
}

// =========================================================================
// 🌟 8. ÚJ: PNEUMATIKA & HIDRAULIKA BLOKKOK (~200 elem)
// =========================================================================
for ($valves = 1; $valves <= 12; $valves++) {
    // CETOP Hidraulika tömbök
    $rows[] = [
        'id' => "cetop3_manifold_v{$valves}",
        'category' => '8. Pneumatika & Hidraulika Blokk',
        'name' => "CETOP 3 (NG6) Hidraulika Szeleptömb ({$valves} Szelepes)",
        'shape' => 'block',
        'base_cost' => 45000 + ($valves * 28000),
        'h' => 120,
        'w' => 140,
        'l' => 80 + ($valves * 60)
    ];

    $rows[] = [
        'id' => "cetop5_manifold_v{$valves}",
        'category' => '8. Pneumatika & Hidraulika Blokk',
        'name' => "CETOP 5 (NG10) Nagyáramlású Hidraulika Tömb ({$valves} Szelepes)",
        'shape' => 'block',
        'base_cost' => 85000 + ($valves * 42000),
        'h' => 160,
        'w' => 180,
        'l' => 100 + ($valves * 85)
    ];

    // Pneumatikus szelepsziget alaplapok
    $stations = $valves * 2;
    $rows[] = [
        'id' => "pneu_island_st{$stations}",
        'category' => '8. Pneumatika & Hidraulika Blokk',
        'name' => "Pneumatikus Szelepsziget Alaplap G1/4 ({$stations} Pozíciós)",
        'shape' => 'block',
        'base_cost' => 28000 + ($stations * 6500),
        'h' => 60,
        'w' => 110,
        'l' => 50 + ($stations * 25)
    ];
}

// =========================================================================
// 🌟 9. ÚJ: HAJTÁSTECHNIKA & MOTOR HÁZAK (~200 elem)
// =========================================================================
$iecSizes = [56, 63, 71, 80, 90, 100, 112, 132, 160, 180, 200, 225, 250, 280, 315];
$flangeTypes = ['B5', 'B14'];

foreach ($iecSizes as $iec) {
    foreach ($flangeTypes as $type) {
        $rows[] = [
            'id' => "motor_flange_iec{$iec}_{$type}",
            'category' => '9. Hajtástechnika & Motor Házak',
            'name' => "IEC {$iec} Villanymotor Csatlakozó Karima ({$type})",
            'shape' => 'disc',
            'base_cost' => 22000 + ($iec * 350),
            'h' => 30 + (int)round($iec * 0.15),
            'w' => $iec * 2,
            'l' => $iec * 2
        ];
    }

    $rows[] = [
        'id' => "worm_gear_box_iec{$iec}",
        'category' => '9. Hajtástechnika & Motor Házak',
        'name' => "Csigakerekes Hajtómű Ház IEC {$iec} Motorhoz",
        'shape' => 'block',
        'base_cost' => 48000 + ($iec * 850),
        'h' => (int)($iec * 1.8),
        'w' => (int)($iec * 1.4),
        'l' => (int)($iec * 1.6)
    ];
}

// =========================================================================
// 🌟 10. ÚJ: ALUMÍNIUM PROFIL VÁZAK (~250 elem)
// =========================================================================
$aluProfiles = [2020, 3030, 4040, 4080, 8080, 9090];
foreach ($aluProfiles as $alu) {
    for ($w = 400; $w <= 2000; $w += 200) {
        for ($l = 800; $l <= 2400; $l += 400) {
            $rows[] = [
                'id' => "aluframe_p{$alu}_{$w}x{$l}",
                'category' => '10. Moduláris AluProfil Vázak',
                'name' => "AluProfil Szerkezeti Keret {$alu} ({$w}x{$l}mm)",
                'shape' => 'frame',
                'base_cost' => 110000 + ($alu * 20) + ($w * 40) + ($l * 35),
                'h' => 1000,
                'w' => $w,
                'l' => $l
            ];
        }
    }
}

// =========================================================================
// INJEKTÁLÁS ADATBÁZISBA (500-AS KÖTEGEKBEN)
// =========================================================================
$chunks = array_chunk($rows, 500);
$totalInserted = 0;

foreach ($chunks as $chunk) {
    $inserted = $db->createCommand()->batchInsert(
        'templates_3d',
        ['id', 'category', 'name', 'shape', 'base_cost', 'h', 'w', 'l'],
        $chunk
    )->execute();
    
    $totalInserted += $inserted;
}

echo "✅ SIKER! {$totalInserted} db 3D Sablon sikeresen be-injektálva az adatbázisba!\n";