<?php
// seed_materials.php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Console alkalmazás inicializálása
$config = file_exists(__DIR__ . '/config/console.php') 
    ? require __DIR__ . '/config/console.php' 
    : require __DIR__ . '/config/web.php';

(new yii\console\Application($config));

$db = Yii::$app->db;

echo "🚀 500+ Ipari Alapanyag Adatbázis Injektálás Indítása...\n";

// Előző adatok törlése a tiszta újratöltéshez
$db->createCommand('TRUNCATE TABLE materials_3d')->execute();

$rows = [];

// =========================================================================
// 1. SZERKEZETI & SZÉNACÉLOK (~80 elem)
// =========================================================================
$steelBases = [
    ['s235jr', 'S235JR Általános Szerkezeti Acél', '1.0038', 1.00],
    ['s275jr', 'S275JR Emelt Szilárdságú Szerkezeti Acél', '1.0044', 1.08],
    ['s355j2', 'S355J2+N Szilárdsági Szerkezeti Acél', '1.0577', 1.15],
    ['s460ql', 'S460QL Finomszemcsés Magas Szilárdságú Acél', '1.8928', 1.45],
    ['s690ql', 'S690QL Extra Nagy Szilárdságú Rugalmas Acél', '1.8928', 1.80],
    ['c10', 'C10 Low Carbon Acél', '1.0301', 1.05],
    ['c15', 'C15 Betétben Edzhető Szénacél', '1.0401', 1.10],
    ['c22', 'C22 Kovácsolható Szénacél', '1.0402', 1.12],
    ['c35', 'C35 Nemesíthető Szénacél', '1.0501', 1.15],
    ['c45', 'C45 / C45E Nemesíthető Gépészeti Acél', '1.1191', 1.20],
    ['c55', 'C55 Közepes Szénacél', '1.0535', 1.25],
    ['c60', 'C60 Rugóacél & Nemesíthető Acél', '1.1221', 1.30],
    ['16mncr5', '16MnCr5 Betétben Edzhető Ötvözött Acél', '1.7131', 1.35],
    ['20mncr5', '20MnCr5 Kopásálló Betétacél', '1.7147', 1.40],
    ['15crmo3', '15CrMo3 Melegszilárd Acél', '1.7335', 1.55],
    ['42crmo4', '42CrMo4 Nemesített Ötvözött Acél', '1.7225', 1.50],
    ['34crnimo6', '34CrNiMo6 Nehézgépészeti Szuper-Acél', '1.6582', 1.85],
    ['30crnimo8', '30CrNiMo8 Nagy Terhelhetőségű Acél', '1.6580', 2.00],
    ['51crv4', '51CrV4 Vanádiumos Rugóacél', '1.8159', 1.60],
    ['11smn30', '11SMn30 Automataacél (Könnyen Forgácsolható)', '1.0715', 1.12],
    ['11smnpb30', '11SMnPb30 Ólmozott Precíziós Automataacél', '1.0718', 1.18],
    ['38smn28', '38SMn28 Nemesíthető Automataacél', '1.0760', 1.28],
    ['st52_3', 'ST52-3 / E355 Precíziós Csőacél', '1.0580', 1.25],
    ['hardox400', 'Hardox 400 Kopásálló Acéllemez', 'Hardox-400', 2.10],
    ['hardox500', 'Hardox 500 Extra Hard Kopásálló Lemez', 'Hardox-500', 2.50]
];

$surfaceFinishes = [
    ['hr', 'Melegen Hengerelt', 1.00],
    ['cd', 'Hidegen Húzott / Kalibrált', 1.12],
    ['gr', 'Köszörült Precíziós', 1.28]
];

foreach ($steelBases as $b) {
    foreach ($surfaceFinishes as $f) {
        $rows[] = [
            'id' => "mat_{$b[0]}_{$f[0]}",
            'category' => '1. Szerkezeti & Szénacélok',
            'name' => "{$b[1]} ({$f[1]})",
            'code' => $b[2],
            'price_factor' => round($b[3] * $f[2], 2)
        ];
    }
}

// =========================================================================
// 2. ROZSDAMENTES & SAVÁLLÓ ACÉLOK (~75 elem)
// =========================================================================
$inoxBases = [
    ['14016', 'AISI 430 Ferrites Rozsdamentes Acél', '1.4016', 1.35],
    ['14021', 'AISI 420 Martenzites Edzhető Inox', '1.4021', 1.60],
    ['14057', 'AISI 431 Magas Szilárdságú Edzhető Inox', '1.4057', 1.85],
    ['14104', 'AISI 430F Automata Rozsdamentes Acél', '1.4104', 1.50],
    ['14301', 'AISI 304 / KO33 Austenites Rozsdamentes Acél (A2)', '1.4301', 1.65],
    ['14305', 'AISI 303 Automata Austenites Inox', '1.4305', 1.70],
    ['14306', 'AISI 304L Extra Alacsony Széntartalmú Inox', '1.4306', 1.75],
    ['14307', 'AISI 304L Standard Saválló Acél', '1.4307', 1.72],
    ['14401', 'AISI 316 Molibdénes Saválló Acél', '1.4401', 2.10],
    ['14404', 'AISI 316L / KO35 Vegyipari Saválló Acél (A4)', '1.4404', 2.20],
    ['14571', 'AISI 316Ti Titán-Stabilizált Saválló Acél', '1.4571', 2.35],
    ['14541', 'AISI 321 Hőálló Austenites Inox', '1.4541', 2.05],
    ['14828', 'AISI 309 Hőálló Skálamentes Acél', '1.4828', 2.60],
    ['14841', 'AISI 314 Magas Hőmérsékletű Hőálló Acél', '1.4841', 3.10],
    ['14539', 'AISI 904L Super Austenites Vegyipari Inox', '1.4539', 4.80],
    ['14462', 'UNS S31803 / 2205 Duplex Rozsdamentes Acél', '1.4462', 3.30],
    ['14410', 'UNS S32750 / 2507 Super Duplex Saválló Acél', '1.4410', 4.20],
    ['14542', '17-4PH / AISI 630 Kiválásos Edzésű Inox', '1.4542', 3.60],
    ['14545', '15-5PH Repülőipari Kiválásos Inox', '1.4545', 4.10],
    ['14534', '13-8Mo Csúcskategóriás Űripari Inox', '1.4534', 5.20],
    ['14529', '254 SMO Tengeri Korrózióálló Ötvözet', '1.4529', 5.50],
    ['14125', 'AISI 440C Extra Kemény Martenzites Inox', '1.4125', 2.80],
    ['14310', 'AISI 301 Rozsdamentes Rugóacél', '1.4310', 1.95],
    ['14835', '253 MA Szuper Hőálló Acél', '1.4835', 3.40],
    ['14550', 'AISI 347 Nióbium-Stabilizált Inox', '1.4550', 2.50]
];

$inoxTypes = [
    ['sheet', 'Selyemfényű Lemez', 1.00],
    ['bar', 'Köracél / Rúd', 1.08],
    ['tube', 'Varrat Nélküli Cső', 1.25]
];

foreach ($inoxBases as $ib) {
    foreach ($inoxTypes as $it) {
        $rows[] = [
            'id' => "mat_inox_{$ib[0]}_{$it[0]}",
            'category' => '2. Rozsdamentes & Saválló Acélok',
            'name' => "{$ib[1]} ({$it[1]})",
            'code' => $ib[2],
            'price_factor' => round($ib[3] * $it[2], 2)
        ];
    }
}

// =========================================================================
// 3. SZERSZÁMACÉLOK & KÜLÖNLEGES ACÉLOK (~60 elem)
// =========================================================================
$toolSteels = [
    ['12379', 'Hidegalakító Szerszámacél X153CrMoV12 (K110 / D2)', '1.2379', 2.40],
    ['12842', 'Olajedzésű Hidegalakító Szerszámacél 90MnCrV8 (K720 / O2)', '1.2842', 1.90],
    ['12510', 'Hidegalakító Precíziós Szerszámacél 100MnCrW4 (O1)', '1.2510', 2.05],
    ['12080', 'Nagy Kopásállóságú Hideg Szerszámacél X210Cr12 (D3)', '1.2080', 2.25],
    ['12343', 'Melegalakító Szerszámacél X37CrMoV5-1 (W300 / H11)', '1.2343', 2.60],
    ['12344', 'Melegalakító Szerszámacél X40CrMoV5-1 (W302 / H13)', '1.2344', 2.75],
    ['12714', 'Kovácsszerszám Acél 56NiCrMoV7 (L6)', '1.2714', 2.30],
    ['12311', 'Műanyagfröccs Szerszámacél 40CrMnMo7 (P20)', '1.2311', 2.00],
    ['12312', 'Műanyagfröccs Szerszámacél Kénes 40CrMnMoS8-6 (P20+S)', '1.2312', 2.10],
    ['12738', 'Előedzett Műanyagfröccs Szerszámacél 30CrNiMo8', '1.2738', 2.45],
    ['12083', 'Rozsdamentes Műanyagfröccs Acél X40Cr14 (420)', '1.2083', 2.70],
    ['12085', 'Rozsdamentes Automata Szerszámacél X33CrS16', '1.2085', 2.55],
    ['13343', 'HSS Gyorsacél S600 / HS6-5-2C (M2)', '1.3343', 3.90],
    ['13243', 'HSS-E Kobaltos Gyorsacél S600 Cobalt (M35)', '1.3243', 4.80],
    ['13207', 'HSS-E 10% Kobaltos Szuper Gyorsacél (M42)', '1.3207', 5.60],
    ['11253', 'Gördülőcsapágy Acél 100Cr6 (SUJ2 / 52100)', '1.1253', 1.70],
    ['cpm10v', 'CPM 10V Pormarkolt Szuper Hideg Szerszámacél', 'CPM-10V', 8.50],
    ['vanadis4', 'Vanadis 4 Extra Uddeholm Pormarkolt Acél', 'Vanadis-4E', 7.90],
    ['elmax', 'Böhler Elmax Pormarkolt Rozsdamentes Szerszámacél', 'Elmax', 9.20],
    ['asp2023', 'Erasteel ASP 2023 Pormarkolt Gyorsacél', 'ASP-2023', 7.50]
];

$toolStates = [
    ['raw', 'Lágyított Állapotban', 1.00],
    ['hardened', 'Vákuumedzett & Megeresztett (58-62 HRC)', 1.40],
    ['ground', 'Köszörült Precíziós Tűrt Lap', 1.55]
];

foreach ($toolSteels as $ts) {
    foreach ($toolStates as $st) {
        $rows[] = [
            'id' => "mat_tool_{$ts[0]}_{$st[0]}",
            'category' => '3. Szerszámacélok',
            'name' => "{$ts[1]} - {$st[1]}",
            'code' => $ts[2],
            'price_factor' => round($ts[3] * $st[2], 2)
        ];
    }
}

// =========================================================================
// 4. ALUMÍNIUM ÖTVÖZETEK (~80 elem)
// =========================================================================
$aluBases = [
    ['1050', 'EN AW-1050A Tiszta Alumínium 99.5%', '3.0255', 1.25],
    ['1200', 'EN AW-1200 Ipari Tiszta Alu', '3.0275', 1.28],
    ['2011', 'EN AW-2011 Automata Alu-Réz Ötvözet (AlCuBiPb)', '3.1655', 1.75],
    ['2014', 'EN AW-2014 Repülőipari Nagy Szilárdságú Alu', '3.1255', 2.10],
    ['2017', 'EN AW-2017A Duralumínium (AlCuMg1)', '3.1315', 1.90],
    ['2024', 'EN AW-2024 Repülőipari Szuper-Dural (AlCuMg2)', '3.1355', 2.30],
    ['3003', 'EN AW-3003 Manganos Alumínium', '3.0515', 1.35],
    ['5005', 'EN AW-5005 Eloxálható Alumínium (AlMg1)', '3.3315', 1.40],
    ['5083', 'EN AW-5083 Tengervízálló / Vegyipari Alu (AlMg4.5Mn)', '3.3547', 1.65],
    ['5754', 'EN AW-5754 Hegeszthető Szerkezeti Alu (AlMg3)', '3.3535', 1.50],
    ['6060', 'EN AW-6060 Profil Alumínium (AlMgSi0.5)', '3.3206', 1.35],
    ['6061', 'EN AW-6061 T6 Amerikai Szabványú Gépészeti Alu', '3.3211', 1.55],
    ['6063', 'EN AW-6063 Eloxálási Minőségű Profil Alu', '3.3206', 1.40],
    ['6082', 'EN AW-6082 T6 Gépészeti Alumínium (AlMgSi1)', '3.2315', 1.45],
    ['7020', 'EN AW-7020 Hegeszthető Cinkes Alu (AlZn4.5Mg1)', '3.4335', 2.15],
    ['7050', 'EN AW-7050 Repülőgép-Szerkezeti Alu', '3.4345', 2.70],
    ['7075', 'EN AW-7075 T651 Fortal / Ergal Szuper-Alu (AlZnMgCu1.5)', '3.4365', 2.40],
    ['plancast', 'PlanCast / ACP5083 Feszültségmentesített Öntött Alu Lemez', 'ACP-5083', 1.85],
    ['certal', 'Certal / EN AW-7022 Szerszámépítő Alu Lemez', '3.4345', 2.60],
    ['alsi10mg', 'AlSi10Mg Öntészeti Alumínium Ötvözet', '3.2381', 1.60]
];

$aluForms = [
    ['raw', 'Standard Megmunkált', 1.00],
    ['anodized', 'Elokszált / Anodizált Felület', 1.22],
    ['precision', 'Precíziós Csiszolt Lemez', 1.35],
    ['t6', 'T6/T651 Hőkezelt Állapot', 1.15]
];

foreach ($aluBases as $ab) {
    foreach ($aluForms as $af) {
        $rows[] = [
            'id' => "mat_alu_{$ab[0]}_{$af[0]}",
            'category' => '4. Alumínium Ötvözetek',
            'name' => "{$ab[1]} ({$af[1]})",
            'code' => $ab[2],
            'price_factor' => round($ab[3] * $af[2], 2)
        ];
    }
}

// =========================================================================
// 5. IPARI MŰANYAGOK (~100 elem)
// =========================================================================
$plasticBases = [
    ['pom_c_wh', 'POM-C Poliacetál Kopolimer (Fehér)', 'POM-C-WH', 1.15],
    ['pom_c_bk', 'POM-C Poliacetál Kopolimer (Fekete)', 'POM-C-BK', 1.15],
    ['pom_h', 'POM-H Delrin Homopolimer', 'POM-H-DELRIN', 1.40],
    ['pom_gf25', 'POM-C GF25 25% Üvegszál Töltött', 'POM-GF25', 1.65],
    ['pa6_nat', 'PA 6 Poliamid Extrudált (Natúr)', 'PA6-NAT', 1.20],
    ['pa6_blk', 'PA 6 Poliamid Extrudált (Fekete)', 'PA6-BLK', 1.20],
    ['pa66', 'PA 6.6 Kemény Poliamid', 'PA66', 1.35],
    ['pa6g_nat', 'PA 6-G Öntött Poliamid (Natúr)', 'PA6G-NAT', 1.45],
    ['pa6g_blue', 'PA 6-G Öntött Poliamid Kenőanyagos (Kék / Zöld)', 'PA6G-OIL', 1.60],
    ['pa6_gf30', 'PA 6-GF30 30% Üvegszálas Poliamid', 'PA6-GF30', 1.70],
    ['pa12', 'PA 12 Alacsony Vízfelvételű Poliamid', 'PA12', 2.10],
    ['ptfe_pure', 'PTFE Tiszta Teflon (Fehér)', 'PTFE-PURE', 2.80],
    ['ptfe_br25', 'PTFE + 25% Bronz Töltött Teflon (Szürke)', 'PTFE-BR25', 3.60],
    ['ptfe_graph', 'PTFE + Grafitozott Teflon (Fekete)', 'PTFE-GRAPH', 3.20],
    ['peek_nat', 'PEEK Tiszta Csúcskategóriás Műanyag', 'PEEK-1000', 8.50],
    ['peek_gf30', 'PEEK-GF30 30% Üvegszálas PEEK', 'PEEK-GF30', 10.20],
    ['peek_cf30', 'PEEK-CF30 30% Szénszálas PEEK', 'PEEK-CF30', 12.80],
    ['peek_mod', 'PEEK-MOD Csapágyazási Kenőanyagos PEEK', 'PEEK-MOD', 11.50],
    ['petp', 'PETP / Ertalyte Méretstabil Műanyag', 'PETP', 1.60],
    ['petp_tx', 'PETP-TX Szilárd Kenőanyagos Ertalyte', 'PETP-TX', 1.90],
    ['pe300', 'PE-HD 300 Nagy Sűrűségű Polietilén', 'PE-300', 0.95],
    ['pe500', 'PE-HMW 500 Ipari Polietilén', 'PE-500', 1.15],
    ['pe1000', 'PE-UHMW 1000 Extra Kopásálló Polietilén', 'PE-1000', 1.35],
    ['pp_nat', 'PP-H Polipropilén Vegyálló Műanyag', 'PP-H', 0.90],
    ['pvc_u', 'PVC-U Kemény Polivinil-Klorid', 'PVC-U', 0.85],
    ['pvdf', 'PVDF Szuper Vegyálló Fluorplaszt', 'PVDF', 4.50],
    ['psu', 'PSU Poliszulfon Hőálló Műanyag', 'PSU', 4.20],
    ['pps', 'PPS Fortron Vegyálló & Hőálló Műanyag', 'PPS', 6.80],
    ['pps_gf40', 'PPS-GF40 40% Üvegszálas PPS', 'PPS-GF40', 7.90],
    ['pei_ultem', 'PEI Ultem 1000 Transzparens Hőálló Műanyag', 'ULTEM-1000', 7.20],
    ['pc_trans', 'PC Polikarbonát Ütésálló (Átlátszó)', 'PC-TRANS', 1.40],
    ['pmma_plexi', 'PMMA Akrilüveg / Plexi', 'PMMA', 1.10],
    ['abs_ind', 'ABS Ipari Műanyag', 'ABS', 1.05]
];

$plasticForms = [
    ['rod', 'Extrudált Rúd / Tömör Kör', 1.00],
    ['plate', 'Vágott Vastag Lemez', 1.10],
    ['precision', 'Precíziós Csiszolt Tűrt Lap', 1.30]
];

foreach ($plasticBases as $pb) {
    foreach ($plasticForms as $pf) {
        $rows[] = [
            'id' => "mat_plast_{$pb[0]}_{$pf[0]}",
            'category' => '5. Ipari Műanyagok',
            'name' => "{$pb[1]} ({$pf[1]})",
            'code' => $pb[2],
            'price_factor' => round($pb[3] * $pf[2], 2)
        ];
    }
}

// =========================================================================
// 6. RÉZ, SÁRGARÉZ & BRONZ (~50 elem)
// =========================================================================
$copperBases = [
    ['cu_etp', 'Elektrolit Vörösréz 99.9% (Cu-ETP / E-Cu)', '2.0065', 2.50],
    ['cu_of', 'Oxigénmentes Vörösréz (Cu-OF)', '2.0040', 2.80],
    ['cucr1zr', 'CuCr1Zr Króm-Cirkónium Réz (Hegesztőelektróda Réz)', '2.1293', 3.40],
    ['cube2', 'CuBe2 Beryllium-Réz Extra Kemény Ötvözet', '2.1247', 6.80],
    ['cuzn39pb3', 'Automata Sárgaréz (CuZn39Pb3 / Ms58)', '2.0401', 1.90],
    ['cuzn36', 'Húzott Sárgaréz (CuZn36 / Ms63)', '2.0335', 2.05],
    ['cuzn40pb2', 'Kovácsolható Sárgaréz (CuZn40Pb2)', '2.0402', 1.95],
    ['cusn6', 'CuSn6 Foszforbronz Rúd', '2.1020', 3.20],
    ['cusn8', 'CuSn8 Valcolható Foszforbronz', '2.1030', 3.40],
    ['cusn12', 'CuSn12-C Öntvénybronz Siklócsapágyhoz', '2.1052', 3.60],
    ['cual10fe3', 'CuAl10Fe3Mn2 Alumíniumbronz Nagy Terhelésre', '2.0936', 3.80],
    ['cual11ni6', 'CuAl11Ni6Fe5 Alumínium-Nikkel Bronz', '2.0975', 4.50],
    ['cuni12zn24', 'Újezüst Ötvözet (CuNi12Zn24)', '2.0730', 3.10],
    ['cuni30mn1fe', 'Kupronikkel Tengervízálló Ötvözet (CuNi30Fe)', '2.0882', 4.90]
];

$copperTypes = [
    ['rod', 'Húzott Körrúd', 1.00],
    ['flat', 'Lapos / Négyszög Rúd', 1.10],
    ['bushing', 'Vastagfalú Cső / Persely', 1.25]
];

foreach ($copperBases as $cb) {
    foreach ($copperTypes as $ct) {
        $rows[] = [
            'id' => "mat_cop_{$cb[0]}_{$ct[0]}",
            'category' => '6. Réz, Sárgaréz & Bronz',
            'name' => "{$cb[1]} ({$ct[1]})",
            'code' => $cb[2],
            'price_factor' => round($cb[3] * $ct[2], 2)
        ];
    }
}

// =========================================================================
// 7. SPECIÁLIS ÖTVÖZETEK, TITÁN & REFRAKTER FÉMEK (~40 elem)
// =========================================================================
$exoticBases = [
    ['ti_gr1', 'Tiszta Titán Grade 1 (Lágy)', '3.7025', 5.80],
    ['ti_gr2', 'Tiszta Titán Grade 2 (Ipari Standard)', '3.7035', 6.50],
    ['ti_gr4', 'Nagy Szilárdságú Tiszta Titán Grade 4', '3.7065', 7.50],
    ['ti_gr5', 'Titán Ötvözet Ti-6Al-4V (Grade 5 Repülőipari)', '3.7165', 9.20],
    ['ti_gr5_eli', 'Titán Grade 23 Ti-6Al-4V ELI (Orvosi Implantátum)', '3.7165-ELI', 11.50],
    ['ti_gr7', 'Titán-Palládium Ötvözet Grade 7 (Extrém Saválló)', '3.7235', 14.00],
    ['inconel_600', 'Inconel 600 NikkelÖtvözet', '2.4816', 10.50],
    ['inconel_625', 'Inconel 625 Melegszilárd Szuperötvözet', '2.4856', 12.80],
    ['inconel_718', 'Inconel 718 Gázturbina Szuperötvözet', '2.4668', 14.20],
    ['hastelloy_c22', 'Hastelloy C-22 Korrózióálló Ötvözet', '2.4602', 15.00],
    ['hastelloy_c276', 'Hastelloy C-276 Vegyipari Szuperötvözet', '2.4819', 16.50],
    ['monel_400', 'Monel 400 Nikkel-Réz Ötvözet', '2.4360', 8.90],
    ['monel_k500', 'Monel K-500 Edzhető NikkelÖtvözet', '2.4375', 10.80],
    ['nimonic_80a', 'Nimonic 80A Szuper Hőálló Ötvözet', '2.4952', 13.50],
    ['tungsten_pure', 'Tiszta Volfrám 99.95% (W)', 'W-9995', 18.00],
    ['molybdenum_pure', 'Tiszta Molibdén (Mo)', 'MO-9995', 14.00],
    ['tantalum_pure', 'Tiszta Tantál (Ta)', 'TA-9995', 28.00]
];

$exoticForms = [
    ['rod', 'Körrúd', 1.00],
    ['sheet', 'Lemez', 1.15]
];

foreach ($exoticBases as $eb) {
    foreach ($exoticForms as $ef) {
        $rows[] = [
            'id' => "mat_exo_{$eb[0]}_{$ef[0]}",
            'category' => '7. Speciális Ötvözetek & Titán',
            'name' => "{$eb[1]} ({$ef[1]})",
            'code' => $eb[2],
            'price_factor' => round($eb[3] * $ef[2], 2)
        ];
    }
}

// =========================================================================
// 8. ÖNTÖTTVASAK & ÖNTVÉNYEK (~30 elem)
// =========================================================================
$castIrons = [
    ['gjl_150', 'GJL-150 / GG15 Lemezgrafitos Öntöttvas', '0.6015', 1.05],
    ['gjl_200', 'GJL-200 / GG20 Lemezgrafitos Öntöttvas', '0.6020', 1.10],
    ['gjl_250', 'GJL-250 / GG25 Sztandard Gépi Öntöttvas', '0.6025', 1.18],
    ['gjl_300', 'GJL-300 / GG30 Finomszemcsés Öntöttvas', '0.6030', 1.25],
    ['gjs_400_15', 'GJS-400-15 / GGG40 Gömbgrafitos Öntöttvas', '0.7040', 1.30],
    ['gjs_500_7', 'GJS-500-7 / GGG50 Gömbgrafitos Öntöttvas', '0.7050', 1.40],
    ['gjs_600_3', 'GJS-600-3 / GGG60 Nagy Szilárdságú Gömbgrafitos Öntvény', '0.7060', 1.50],
    ['gjs_700_2', 'GJS-700-2 / GGG70 Edzett Gömbgrafitos Vas', '0.7070', 1.65],
    ['ni_resist', 'Ni-Resist Austenites Korrózióálló Öntöttvas', '0.6660', 3.20],
    ['ni_hard', 'Ni-Hard Extra Kopásálló Öntöttvas', '0.5900', 2.80]
];

$castTypes = [
    ['raw', 'Öntött Nyers Tuskó', 1.00],
    ['machined', 'Előmunkált / Hámozott Rúd', 1.18],
    ['hollow', 'Vastagfalú Hüvely', 1.28]
];

foreach ($castIrons as $ci) {
    foreach ($castTypes as $ct) {
        $rows[] = [
            'id' => "mat_cast_{$ci[0]}_{$ct[0]}",
            'category' => '8. Öntöttvasak & Öntvények',
            'name' => "{$ci[1]} ({$ct[1]})",
            'code' => $ci[2],
            'price_factor' => round($ci[3] * $ct[2], 2)
        ];
    }
}

// =========================================================================
// 9. KERÁMIÁK & KOMPOZITOK (~30 elem)
// =========================================================================
$composites = [
    ['al2o3_99', 'Alumínium-Oxid Kerámia 99.5% (Al2O3)', 'AL2O3-99', 6.80],
    ['zro2_yttria', 'Cirkónium-Oxid Kerámia (ZrO2 Y-TZP)', 'ZRO2-YTZP', 9.50],
    ['si3n4', 'Szilícium-Nitrid Kerámia (Si3N4)', 'SI3N4', 12.00],
    ['sic_sintered', 'Szinterezett Szilícium-Karbid (SSiC)', 'SSIC', 11.00],
    ['macor', 'Macor Megmunkálható Üvegkerámia', 'MACOR', 8.20],
    ['cfrp_3k', 'CFRP Szénszálas Kompozit (3K Twill Carbon)', 'CFRP-3K', 5.50],
    ['cfrp_ud', 'CFRP Unidirekcionális Szénszál Rúd', 'CFRP-UD', 6.20],
    ['g10_fr4', 'G10 / FR4 Üvegszálas Epoxi Lemez', 'G10-FR4', 1.80],
    ['bakelite_hp', 'Bakelit / HPERTINAX Textilbakelit Lemez (Hgw 2082)', 'HGW-2082', 1.35],
    ['celleron', 'Celleron Műszaki Textilbakelit Rúd', 'CC-201', 1.45]
];

$compositeTypes = [
    ['plate', 'Kompozit Lemez', 1.00],
    ['rod', 'Körrúd / Profil', 1.15]
];

foreach ($composites as $cp) {
    foreach ($compositeTypes as $cpt) {
        $rows[] = [
            'id' => "mat_comp_{$cp[0]}_{$cpt[0]}",
            'category' => '9. Kerámiák & Kompozitok',
            'name' => "{$cp[1]} ({$cpt[1]})",
            'code' => $cp[2],
            'price_factor' => round($cp[3] * $cpt[2], 2)
        ];
    }
}

// =========================================================================
// 10. ELASZTOMEREK & IPARI GUMIK (~30 elem)
// =========================================================================
$rubbers = [
    ['pur_70', 'Poliuretán PUR 70 Shore A (Sárga)', 'PUR-70SH', 1.60],
    ['pur_90', 'Poliuretán PUR 90 Shore A (Piros)', 'PUR-90SH', 1.75],
    ['pur_95', 'Poliuretán PUR 95 Shore A Vágólap (Fekete)', 'PUR-95SH', 1.90],
    ['nbr_70', 'NBR Olajálló Gumi 70 Shore A', 'NBR-70', 1.10],
    ['fkm_viton', 'FKM Viton Vegyálló & Hőálló Gumi 75 Shore A', 'FKM-VITON', 4.20],
    ['epdm_70', 'EPDM Időjárásálló Gumi', 'EPDM-70', 1.20],
    ['silicone_wh', 'Szilikongumi Élelmiszeripari (Fehér / Átlátszó)', 'SILICONE-FDA', 2.10],
    ['neoprene_cr', 'Neoprén / CR Ipari Gumi', 'CR-NEOPRENE', 1.40],
    ['nr_natural', 'Természetes Gumi NR Para', 'NR-PARA', 1.25],
    ['fsi_fluor', 'Fluor-Szilikon Extrém Ipari Gumi', 'FSI-900', 6.50]
];

$rubberTypes = [
    ['sheet', 'Gumi Lemez / Munkalap', 1.00],
    ['rod', 'Tömör Rúd / Hasáb', 1.15]
];

foreach ($rubbers as $rb) {
    foreach ($rubberTypes as $rt) {
        $rows[] = [
            'id' => "mat_rub_{$rb[0]}_{$rt[0]}",
            'category' => '10. Elasztomerek & Ipari Gumik',
            'name' => "{$rb[1]} ({$rt[1]})",
            'code' => $rb[2],
            'price_factor' => round($rb[3] * $rt[2], 2)
        ];
    }
}

// =========================================================================
// INJEKTÁLÁS ADATBÁZISBA (500-AS KÖTEGEKBEN)
// =========================================================================
$chunks = array_chunk($rows, 500);
$totalInserted = 0;

foreach ($chunks as $chunk) {
    $inserted = $db->createCommand()->batchInsert(
        'materials_3d',
        ['id', 'category', 'name', 'code', 'price_factor'],
        $chunk
    )->execute();
    
    $totalInserted += $inserted;
}

echo "✅ SIKER! {$totalInserted} db Ipari Alapanyag sikeresen be-injektálva az adatbázisba!\n";