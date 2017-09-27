<?php namespace Ahead4\Bus;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\CallQueuedHandler as IlluminateCallQueuedHandler;

class CallQueuedHandler extends IlluminateCallQueuedHandler
{
	/**
	 * Handle the queued job.
	 *
	 * @param  \Illuminate\Contracts\Queue\Job  $job
	 * @param  array  $data
	 * @return void
	 */
	public function call(Job $job, array $data)
	{
		$pipes = isset($data['pipes']) ? $data['pipes'] : [];
		
		$command = $this->setJobInstanceIfNecessary(
			$job,
			unserialize($data['command'])
		);

		$this->dispatcher->pipeThrough($pipes)->dispatchNow($command, $this->resolveHandler($job, $command));

		if (!$job->isDeletedOrReleased()) {
			$job->delete();
		}
	}
}
