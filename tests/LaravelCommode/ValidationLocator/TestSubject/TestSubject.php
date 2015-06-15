<?php

namespace LaravelCommode\ValidationLocator\TestSubject;

use LaravelCommode\ValidationLocator\Validators\Validator;

class TestSubject extends Validator
{
    public function getRules($isNew = true)
    {
        return ['name' => 'required'];
    }

    public function getMessages()
    {
        return [];
    }
}
