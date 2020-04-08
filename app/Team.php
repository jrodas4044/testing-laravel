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

    public function add($user)
    {
        $this->guardAgainInstTooManyMembers();

        $method = $user instanceof User ? 'save' : 'saveMany';

        $this->members()->$method($user);
    }

    protected function guardAgainInstTooManyMembers() {
        if ($this->count() >= $this->size) {
            throw new \Exception('Exception');
        }
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
