<?php

/*
 * This file is part of Fixhub.
 *
 * Copyright (C) 2016 Fixhub.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fixhub\Presenters;

use Illuminate\Support\Facades\Config;
use McCool\LaravelAutoPresenter\BasePresenter;
use Fixhub\Models\Project;

/**
 * The view presenter for a project class.
 */
class ProjectPresenter extends CommandPresenter
{
    /**
     * Returns the build status needed by CCTray
     * These strings can not be translated.
     *
     * @return string
     */
    public function cc_tray_status()
    {
        if ($this->wrappedObject->status === Project::FINISHED || $this->wrappedObject->status === Project::FAILED) {
            return 'Sleeping';
        } elseif ($this->wrappedObject->status === Project::DEPLOYING) {
            return 'Building';
        } elseif ($this->wrappedObject->status === Project::PENDING) {
            return 'Pending';
        }

        return 'Unknown';
    }

    /**
     * Gets the translated project status string.
     *
     * @return string
     */
    public function readable_status()
    {
        if ($this->wrappedObject->status === Project::FINISHED) {
            return trans('projects.finished');
        } elseif ($this->wrappedObject->status === Project::DEPLOYING) {
            return trans('projects.deploying');
        } elseif ($this->wrappedObject->status === Project::FAILED) {
            return trans('projects.failed');
        } elseif ($this->wrappedObject->status === Project::PENDING) {
            return trans('projects.pending');
        }

        return trans('projects.not_deployed');
    }

    /**
     * Gets the CSS icon class for the project status.
     *
     * @return string
     */
    public function icon()
    {
        if ($this->wrappedObject->status === Project::FINISHED) {
            return 'checkmark-round';
        } elseif ($this->wrappedObject->status === Project::DEPLOYING) {
            return 'load-c fixhub-spin';
        } elseif ($this->wrappedObject->status === Project::FAILED) {
            return 'alert';
        } elseif ($this->wrappedObject->status === Project::PENDING) {
            return 'clock';
        }

        return 'help';
    }

    /**
     * Gets the CSS class for the project status.
     *
     * @return string
     */
    public function css_class()
    {
        if ($this->wrappedObject->status === Project::FINISHED) {
            return 'success';
        } elseif ($this->wrappedObject->status === Project::DEPLOYING) {
            return 'warning';
        } elseif ($this->wrappedObject->status === Project::FAILED) {
            return 'danger';
        } elseif ($this->wrappedObject->status === Project::PENDING) {
            return 'info';
        }

        return 'primary';
    }

    /**
     * Show the application status.
     *
     * @return string
     */
    public function app_status()
    {
        $status = $this->applicationCheckUrlStatus();

        if ($status['length'] === 0) {
            return trans('app.not_applicable');
        }

        return ($status['length'] - $status['missed']) . ' / ' . $status['length'];
    }

    /**
     * Show the application status css.
     *
     * @return string
     */
    public function app_status_css()
    {
        $status = $this->applicationCheckUrlStatus();

        if ($status['length'] === 0) {
            return 'warning';
        } elseif ($status['missed']) {
            return 'danger';
        }

        return 'success';
    }

    /**
     * Show heartbeat status count.
     *
     * @return string
     */
    public function heartbeat_status()
    {
        $status = $this->heartbeatsStatus();

        if ($status['length'] === 0) {
            return trans('app.not_applicable');
        }

        return ($status['length'] - $status['missed']) . ' / ' . $status['length'];
    }

    /**
     * The application heartbeat status css.
     *
     * @return string
     */
    public function heartbeat_status_css()
    {
        $status = $this->heartbeatsStatus();

        if ($status['length'] === 0) {
            return 'warning';
        } elseif ($status['missed']) {
            return 'danger';
        }

        return 'success';
    }

    /**
     * Gets an icon which represents the repository type.
     *
     * @return string
     */
    public function type_icon()
    {
        $details = $this->accessDetails();

        if (isset($details['domain'])) {
            if (preg_match('/github\.com/', $details['domain'])) {
                return 'ion-social-github';
            } elseif (preg_match('/gitlab\.com/', $details['domain'])) {
                return 'fa-gitlab';
            } elseif (preg_match('/bitbucket/', $details['domain'])) {
                return 'fa-bitbucket';
            } elseif (preg_match('/amazonaws\.com/', $details['domain'])) {
                return 'fa-amazon';
            }
        }

        return 'ion-cube';
    }
}
