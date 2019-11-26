<?php

namespace swede2k\XF2Bridge;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;


class XF2Guard implements Guard
{
    protected $xenforo;
    protected $user;
    protected $provider;

    public function __construct(XF2Bridge $xenforo, $provider)
    {
        $this->xenforo  = $xenforo;
        $this->provider  = $provider;
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function guest()
    {
        return ! $this->check();
    }

    public function user()
    {
        if(! is_null($this->user))
        {
            return $this->user;
        }
        $user = null;

        if($this->xenforo->isLoggedIn())
        {
            $user = $this->xenforo->getVisitorObject();

            $user = DB::table($this->provider)->updateOrInsert([
                'xf_user_id' => $user->getUserId(),
                'email' => $user->email,
                'username' => $user->getName()
            ]);
            
            $this->user = $user;
        }
        
        /** @todo Implement Authenticable */

         return  $this->user;
    }

    public function id()
    {
        if($this->user())
        {
            return $this->user()->id;
        }
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
}
