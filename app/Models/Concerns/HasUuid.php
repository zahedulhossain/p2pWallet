<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the trait by bootTraits method of eloquent model.
     *
     * @return void
     */
    protected static function bootHasUuid()
    {
        static::creating(static fn (Model $model) => $model->fillUuid());
    }

    public function fillUuid()
    {
        if ($this->getAttribute('uuid') === null) {
            $this->setAttribute('uuid', Str::uuid()->toString());
        }
    }
}
