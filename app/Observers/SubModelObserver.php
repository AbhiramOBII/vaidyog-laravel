<?php

namespace App\Observers;

use App\Traits\RecalculatesProfileCompleteness;
use Illuminate\Database\Eloquent\Model;

class SubModelObserver
{
    use RecalculatesProfileCompleteness;

    public function saved(Model $model): void
    {
        $this->recalculateCompleteness($model->user_id);
    }

    public function deleted(Model $model): void
    {
        $this->recalculateCompleteness($model->user_id);
    }
}
