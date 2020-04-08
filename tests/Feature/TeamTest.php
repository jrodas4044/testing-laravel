<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use DatabaseMigrations;

   public function test_it_a_team_has_a_name()
   {
       $name = 'Acme';

       $team = new Team([
           'name' => $name
       ]);

       $this->assertEquals($name, $team->name);
   }

   public function test_it_a_team_can_add_multiple_members_at_once() {
       $team = factory(Team::class)->create();

       $users = factory(User::class, 2)->create();

       $team->add($users);

       $this->assertEquals(2, $team->count());
   }

   public function test_it_a_team_can_add_members()
   {
        $team = factory(Team::class)->create();

        $user = factory(User::class)->create();
        $userTwo = factory(User::class)->create();

        $team->add($user);
        $team->add($userTwo);

        $this->assertEquals(2, $team->count());
   }

   public function test_it_a_team_has_a_maximum_size()
   {
       $team = factory(Team::class)->create(['size' => 2]);

       $userOne = factory(User::class)->create();
       $userTwo = factory(User::class)->create();

       $team->add($userOne);
       $team->add($userTwo);

       $this->assertEquals(2, $team->count());

       $this->expectException('Exception');

       $userThree = factory(User::class)->create();
       $team->add($userThree);
   }

   public function test_it_at_can_remove_a_member_at_once()
   {
       $team = factory(Team::class)->create();

       $users = factory(User::class, 2)->create();

       $team->add($users);

       $team->remove($users[0]);

       $this->assertEquals(1, $team->count());
   }

   public function test_it_a_team_can_remove_more_than_one_member_at_once()
   {
       $team = factory(Team::class)->create(['size' => 3]);
       $users = factory(User::class, 3)->create();
       $team->add($users);

       $team->remove($users->slice(0,2));

       $this->assertEquals(1, $team->count());
   }

   public function test_it_a_team_can_remove_all_members_ar_once()
   {
       $team = factory(Team::class)->create();
       $users = factory(User::class, 2)->create();
       $team->add($users);

       $team->restart();

       $this->assertEquals(0, $team->count());
   }
}
