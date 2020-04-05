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
            throw new \Exception();
        }
    }

    public function count()
    {
        return $this->members()->count();
    }
}
