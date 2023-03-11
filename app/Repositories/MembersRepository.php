<?php


namespace App\Repositories;


use App\Models\Members;
use App\Models\MemberType;

class MembersRepository extends Repository
{
    public function __construct(Members $members)
    {
        $this->model =  $members;
    }
}