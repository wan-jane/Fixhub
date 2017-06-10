<?php

/*
 * This file is part of Fixhub.
 *
 * Copyright (C) 2016 Fixhub.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixhub\Http\Requests;

use Fixhub\Http\Requests\Request;

/**
 * Request for validating servers.
 */
class StoreServerRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|max:255',
            'user'         => 'required|max:255',
            'ip_address'   => 'required|host',
            'path'         => 'required',
            'add_commands' => 'boolean',
            'project_id'   => 'required|integer|exists:projects,id',
        ];
    }
}
