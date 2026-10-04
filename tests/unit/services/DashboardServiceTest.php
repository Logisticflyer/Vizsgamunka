<?php

namespace tests\unit\services;

use Codeception\Test\Unit;
use app\services\DashboardService;

/**
 * DashboardServiceUnitTest - A DashboardService üzleti logikájának egységtesztje.
 */
class DashboardServiceTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private DashboardService $dashboardService;

    protected function _before()
    {
        // Minden egyes tesztmetódus előtt lefut, példányosítja a Service-t
        $this->dashboardService = new DashboardService();
    }

    /**
     * Teszteli, hogy az admin dashboard adatai a megfelelő struktúrában érkeznek-e meg.
     */
    public function testGetAdminDashboardDataReturnsValidStructure()
    {
        $data = $this->dashboardService->getAdminDashboardData();

        // 1. Ellenőrizzük, hogy tömböt kaptunk-e vissza
        $this->assertIsArray($data, 'A getAdminDashboardData() metódusnak tömbbel kell visszatérnie.');

        // 2. Ellenőrizzük a kötelező mezők/kulcsok meglétét
        $this->assertArrayHasKey('totalOffers', $data, 'Hiányzik a totalOffers kulcs.');
        $this->assertArrayHasKey('recentOffers', $data, 'Hiányzik a recentOffers kulcs.');
        $this->assertArrayHasKey('totalKnowledge', $data, 'Hiányzik a totalKnowledge kulcs.');

        // 3. Ellenőrizzük az adattípusokat
        $this->assertIsInt($data['totalOffers']);
        $this->assertIsArray($data['recentOffers']);
        $this->assertIsInt($data['totalKnowledge']);
    }

    /**
     * Teszteli, hogy a felhasználói ajánlatok lekérése tömbbel tér-e vissza.
     */
    public function testGetUserOffersReturnsArray()
    {
        // Létrehozunk egy mock (szimulált) user objektumot
        $dummyUser = (object)[
            'email' => 'test@example.com',
            'username' => 'testuser'
        ];

        $offers = $this->dashboardService->getUserOffers($dummyUser);

        $this->assertIsArray($offers, 'A getUserOffers() metódusnak tömböt kell visszaadnia.');
    }
}