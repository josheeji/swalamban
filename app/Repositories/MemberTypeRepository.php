<?php


namespace App\Repositories;


use App\Models\MemberType;

class MemberTypeRepository extends Repository
{
    public function __construct(MemberType $memberType)
    {
        $this->model = $memberType;
    }
}