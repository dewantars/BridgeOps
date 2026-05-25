<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    #[Test]
    public function it_can_check_admin_role(): void
    {
        $admin = new User(['role' => 'admin']);

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isPm());
        $this->assertFalse($admin->isClient());
    }

    #[Test]
    public function it_can_check_pm_role(): void
    {
        $pm = new User(['role' => 'pm']);

        $this->assertFalse($pm->isAdmin());
        $this->assertTrue($pm->isPm());
        $this->assertFalse($pm->isClient());
    }

    #[Test]
    public function it_can_check_client_role(): void
    {
        $client = new User(['role' => 'client']);

        $this->assertFalse($client->isAdmin());
        $this->assertFalse($client->isPm());
        $this->assertTrue($client->isClient());
    }

    #[Test]
    public function admin_can_manage_projects(): void
    {
        $admin = new User(['role' => 'admin']);
        $this->assertTrue($admin->canManageProjects());
    }

    #[Test]
    public function pm_can_manage_projects(): void
    {
        $pm = new User(['role' => 'pm']);
        $this->assertTrue($pm->canManageProjects());
    }

    #[Test]
    public function client_cannot_manage_projects(): void
    {
        $client = new User(['role' => 'client']);
        $this->assertFalse($client->canManageProjects());
    }
}
