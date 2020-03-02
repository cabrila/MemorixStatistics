<?php

namespace Tests\Feature;

use App\Action;
use App\Session;
use App\Variable;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /** @test */
    public function a_user_can_view_all_sessions()
    {
        $this->withoutExceptionHandling();

        $sessions = factory(Session::class, 2)->create();

        $response = $this->json("GET", "api/sessions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user" => $sessions[0]->user,
                        "client" => $sessions[0]->client,
                        "platform" => $sessions[0]->platform,
                        "created_at" => $sessions[0]->created_at,
                    ],
                    [
                        "user" => $sessions[1]->user,
                        "client" => $sessions[1]->client,
                        "platform" => $sessions[1]->platform,
                        "created_at" => $sessions[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_all_sessions_with_relations()
    {
        $this->withoutExceptionHandling();

        $sessions = factory(Session::class, 2)->create();

        $response = $this->json("GET", "api/sessions?include=actions,variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "user" => $sessions[0]->user,
                        "client" => $sessions[0]->client,
                        "platform" => $sessions[0]->platform,
                        "created_at" => $sessions[0]->created_at,
                        "actions" => [],
                        "variables" => [],
                    ],
                    [
                        "user" => $sessions[1]->user,
                        "client" => $sessions[1]->client,
                        "platform" => $sessions[1]->platform,
                        "created_at" => $sessions[1]->created_at,
                        "actions" => [],
                        "variables" => [],
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_session()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();

        $response = $this->json("GET", "api/sessions/$session->id");

        $response
            ->assertStatus(200)
            ->assertJson([
                "user" => $session->user,
                "client" => $session->client,
                "platform" => $session->platform,
                "created_at" => $session->created_at,
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_session_with_its_relations()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();

        $response = $this->json("GET", "api/sessions/{$session->id}?include=actions,variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "user" => $session->user,
                "client" => $session->client,
                "platform" => $session->platform,
                "created_at" => $session->created_at,
                "actions" => [],
                "variables" => [],
            ]);
    }

    /** @test */
    public function a_user_can_create_a_new_session()
    {
        $this->withoutExceptionHandling();

        $data = [
            "user" => $this->faker->randomNumber(),
            "client" => $this->faker->randomElement(["Browser", "Unity"]),
            "platform" => $this->faker->randomElement(["OSX", "Windows", "Android", "iPhone"]),
        ];

        $response = $this->json("POST", "api/sessions", $data);

        $this->assertDatabaseHas("sessions", $data);

        $response
            ->assertStatus(201)
            ->assertJson($data);
    }

    /** @test */
    public function a_user_can_delete_a_session()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();

        $response = $this->json("DELETE", "api/sessions/$session->id");

        $this->assertDatabaseMissing("sessions", [
            "user" => $session->user,
            "client" => $session->client,
            "platform" => $session->platform,
            "created_at" => $session->created_at,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                "user" => $session->user,
                "client" => $session->client,
                "platform" => $session->platform,
                "created_at" => $session->created_at,
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_sessions_actions()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();
        $actions = factory(Action::class, 2)->create(["session_id" => $session->id]);

        $response = $this->json("GET", "api/sessions/$session->id/actions");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "location" => $actions[0]->location,
                        "target" => $actions[0]->target,
                        "created_at" => $actions[0]->created_at,
                    ],
                    [
                        "location" => $actions[1]->location,
                        "target" => $actions[1]->target,
                        "created_at" => $actions[1]->created_at,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }

    /** @test */
    public function a_user_can_view_a_specific_sessions_actions_variables()
    {
        $this->withoutExceptionHandling();

        $session = factory(Session::class)->create();
        $action = factory(Action::class)->create(["session_id" => $session->id]);
        $variables = factory(Variable::class, 2)->create(["action_id" => $action->id]);

        $response = $this->json("GET", "api/sessions/$session->id/variables");

        $response
            ->assertStatus(200)
            ->assertJson([
                "data" => [
                    [
                        "variable" => $variables[0]->variable,
                        "value" => $variables[0]->value,
                    ],
                    [
                        "variable" => $variables[1]->variable,
                        "value" => $variables[1]->value,
                    ],
                ],
                "links" => [],
                "meta" => [],
            ]);
    }
}