<?php
/**
 * Created by PhpStorm.
 * User: xnw
 * Date: 2017/6/10
 * Time: 上午9:46
 */

namespace Fixhub\Bus\Jobs;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GitCheckoutNewRelease extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    use DispatchesJobs;

    public function handle()
    {

    }
}