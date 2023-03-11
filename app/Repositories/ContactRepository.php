<?php
/**
 * Created by PhpStorm.
 * Author: Kokil Thapa <thapa.kokil@gmail.com>
 * Date: 6/27/18
 * Time: 12:25 PM
 */

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Repository;

class ContactRepository extends Repository
{
    public function __construct(Contact $Contact)
    {
        $this->model = $Contact;
    }


    public function update($id, $inputs)
    {
        $update = $this->model->findOrFail($id);
        $update->fill($inputs)->save();
        return $update;
    }

}