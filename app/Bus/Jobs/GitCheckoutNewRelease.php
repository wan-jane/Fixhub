<?php
/**
 * Created by PhpStorm.
 * User: xnw
 * Date: 2017/6/10
 * Time: 上午9:46
 */

namespace Fixhub\Bus\Jobs;


use Carbon\Carbon;
use  Illuminate\Support\Facades\Log;
use Fixhub\Bus\Events\DeployFinished;
use Fixhub\Models\Deployment;
use Fixhub\Models\Project;
use Fixhub\Models\Server;
use Fixhub\Models\ServerLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Fixhub\Services\Scripts\Runner as Process;

class GitCheckoutNewRelease extends Job //implements ShouldQueue
{
    //use InteractsWithQueue, SerializesModels;

    /**
     * @var Deployment $deployment
     */
    private $deployment;

    /**
     * @var Project $project
     */
    private $project;

    private $private_key;

    public function __construct(Deployment $deployment)
    {
        $this->deployment = $deployment;
        $this->project = $deployment->project;

    }

    public function handle()
    {
        $failed = 0;

        $this->deployment->started_at = Carbon::now();
        $this->deployment->status     = Deployment::DEPLOYING;
        $this->deployment->save();

        $this->deployment->project->status = Project::DEPLOYING;
        $this->deployment->project->save();
        /**
         * @var Server $server
         */
        foreach ($this->project->servers as $server) {
            try {

                /**
                 * @var ServerLog $sl
                 */

                if (!$server->deploy_code) {
                    continue;
                }

                $this->private_key = tempnam(storage_path('app/'), 'sshkey');
                file_put_contents($this->private_key, $this->deployment->project->key->private_key);
                echo $this->private_key;
                $process = new Process('xnw.Release', [
                    'project_path'   => $server->clean_path,
                    'branch'   => $this->deployment->branch,
                    'server_name'   => $server->name,
                ]);
                $process->setServer($server, $this->private_key)
                    ->run();
                if (!$process->isSuccessful()) {
                    $failed += 1;
                    continue;
                }
            } catch (\Exception $e) {
                Log::error($e->getTraceAsString());
                Log::error($e->getMessage());
            }
        }


        if ($failed > 0) {
            $this->deployment->status = Deployment::FAILED;
        }

        $this->deployment->save();

        event(new DeployFinished($this->deployment));

        unlink($this->private_key);
    }
}