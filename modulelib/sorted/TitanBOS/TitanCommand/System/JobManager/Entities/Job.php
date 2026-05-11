<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;






class Job extends Model
{
    protected $guarded = [];
    protected $dates = ['start_date','completed_at'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        try {
            if (Schema::hasTable('work_orders')) {
                $this->setTable('work_orders');
            } elseif (Schema::hasTable('jobs')) {
                $this->setTable('jobs');
            } else {
                $this->setTable('jobs');
            }
        } catch (\Throwable $e) {
            $this->setTable('jobs');
        }
    }
}
