<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'size'];

    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function add($users)
    {
        $this->guardAgainInstTooManyMembers($this->extractNewUserCount($users));

        $method = $users instanceof User ? 'save' : 'saveMany';

        $this->members()->$method($users);
    }

    protected function guardAgainInstTooManyMembers($newUsersCount)
    {
        $newTeamCount = $this->count() + $newUsersCount;
        if ($newTeamCount > $this->size) {
            throw new \Exception('limit size');
        }
    }

    protected function extractNewUserCount($users)
    {
        return ($users instanceof User) ? 1 : $users->count();
    }

    public function remove($users = null)
    {
        if ($users instanceof User) {
            return $users->leavTeam();
        }

        return $this->removeMany($users);

    }

    public function removeMany($users)
    {

        $this->members()
            ->whereIn('id', $users->pluck('id'))
            ->update(['team_id' => null]);

    }

    public function restart()
    {
        $this->members()->update(['team_id' => null]);
    }

    public function count()
    {
        return $this->members()->count();
    }
}
