<?php

namespace App\Repositories;

use App\Models\EmailLog;

class EmailLogRepository extends Repository
{
    public function __construct(EmailLog $log)
    {
        $this->model = $log;
    }

    public function searchAndPaginate($data, $paginate)
    {
        $query = $this->model->with(['user'])
            ->selectRaw('*, DATE_FORMAT(created_at, "%D %b, %Y  %h:%i %p") as created_on');
        if (!empty($data['from_date']))
            $query->whereDate("created_at", '>=', $data['from_date']);
        if (!empty($data['to_date']))
            $query->whereDate("created_at", '<=', $data['to_date']);

        return $query->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }
}
