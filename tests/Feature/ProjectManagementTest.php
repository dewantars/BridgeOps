<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $pm;
    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin  = User::factory()->create(['role' => 'admin']);
        $this->pm     = User::factory()->create(['role' => 'pm']);
        $this->client = User::factory()->create(['role' => 'client']);
    }

    // ─── Index ───────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_see_projects_index(): void
    {
        Project::factory()->count(3)->create();

        $this->actingAs($this->admin)
            ->get('/projects')
            ->assertOk()
            ->assertViewIs('projects.index');
    }

    #[Test]
    public function client_only_sees_their_own_projects_on_index(): void
    {
        $ownProject   = Project::factory()->create();
        $otherProject = Project::factory()->create();

        $ownProject->members()->attach($this->client->id, ['role' => 'client']);

        $this->actingAs($this->client)
            ->get('/projects')
            ->assertOk()
            ->assertSee($ownProject->name)
            ->assertDontSee($otherProject->name);
    }

    // ─── Create ──────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_access_create_project_page(): void
    {
        $this->actingAs($this->admin)
            ->get('/projects/create')
            ->assertOk()
            ->assertViewIs('projects.create');
    }

    #[Test]
    public function pm_can_access_create_project_page(): void
    {
        $this->actingAs($this->pm)
            ->get('/projects/create')
            ->assertOk();
    }

    #[Test]
    public function client_cannot_access_create_project_page(): void
    {
        $this->actingAs($this->client)
            ->get('/projects/create')
            ->assertForbidden();
    }

    // ─── Store ───────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_create_a_new_project(): void
    {
        $this->actingAs($this->admin)
            ->post('/projects', [
                'name'        => 'Project Alpha',
                'client_name' => 'Acme Corp',
                'description' => 'Test project description',
                'status'      => 'on_track',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'name'        => 'Project Alpha',
            'client_name' => 'Acme Corp',
            'status'      => 'on_track',
        ]);
    }

    #[Test]
    public function project_creation_auto_adds_creator_as_member(): void
    {
        $this->actingAs($this->admin)
            ->post('/projects', [
                'name'        => 'Project Beta',
                'client_name' => 'Beta Corp',
                'status'      => 'on_track',
            ]);

        $project = Project::where('name', 'Project Beta')->first();

        $this->assertNotNull($project);
        $this->assertDatabaseHas('project_members', [
            'project_id' => $project->id,
            'user_id'    => $this->admin->id,
        ]);
    }

    #[Test]
    public function client_cannot_create_a_project(): void
    {
        $this->actingAs($this->client)
            ->post('/projects', [
                'name'        => 'Unauthorized Project',
                'client_name' => 'Some Corp',
                'status'      => 'on_track',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('projects', ['name' => 'Unauthorized Project']);
    }

    #[Test]
    public function project_creation_requires_name_and_client_name(): void
    {
        $this->actingAs($this->admin)
            ->post('/projects', ['status' => 'on_track'])
            ->assertSessionHasErrors(['name', 'client_name']);
    }

    #[Test]
    public function project_creation_validates_status_enum(): void
    {
        $this->actingAs($this->admin)
            ->post('/projects', [
                'name'        => 'Invalid Status Project',
                'client_name' => 'Corp',
                'status'      => 'invalid_status',
            ])
            ->assertSessionHasErrors(['status']);
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_view_any_project(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->admin)
            ->get("/projects/{$project->id}")
            ->assertOk()
            ->assertViewIs('projects.show');
    }

    #[Test]
    public function client_cannot_view_project_they_are_not_a_member_of(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->client)
            ->get("/projects/{$project->id}")
            ->assertForbidden();
    }

    #[Test]
    public function client_can_view_project_they_are_a_member_of(): void
    {
        $project = Project::factory()->create();
        $project->members()->attach($this->client->id, ['role' => 'client']);

        $this->actingAs($this->client)
            ->get("/projects/{$project->id}")
            ->assertOk();
    }

    // ─── Update ───────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_update_a_project(): void
    {
        $project = Project::factory()->create(['name' => 'Old Name']);

        $this->actingAs($this->admin)
            ->put("/projects/{$project->id}", [
                'name'        => 'Updated Name',
                'client_name' => $project->client_name,
                'status'      => 'completed',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('projects', [
            'id'     => $project->id,
            'name'   => 'Updated Name',
            'status' => 'completed',
        ]);
    }

    #[Test]
    public function client_cannot_update_a_project(): void
    {
        $project = Project::factory()->create(['name' => 'Original Name']);

        $this->actingAs($this->client)
            ->put("/projects/{$project->id}", [
                'name'        => 'Hacked Name',
                'client_name' => 'Hacker',
                'status'      => 'on_track',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('projects', ['name' => 'Original Name']);
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    #[Test]
    public function admin_can_delete_a_project(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->admin)
            ->delete("/projects/{$project->id}")
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    #[Test]
    public function client_cannot_delete_a_project(): void
    {
        $project = Project::factory()->create();

        $this->actingAs($this->client)
            ->delete("/projects/{$project->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }
}
