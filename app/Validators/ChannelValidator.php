<?php

/*
 * This file is part of Fixhub.
 *
 * Copyright (C) 2016 Fixhub.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixhub\Validators;

/**
 * Class for validating slack channels.
 */
class ChannelValidator
{
    /**
     * Validate the the channel name is valid for slack, i.e. starts with # or @.
     *
     * @param  string $attribute
     * @param  mixed  $value
     * @param  mixed  $parameters
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($attribute, $value, $parameters)
    {
        $first_character = substr($value, 0, 1);

        return (($first_character === '#' || $first_character === '@') && strlen($value) > 1);
    }
}
