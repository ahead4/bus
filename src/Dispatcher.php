<?php namespace Ahead4\Bus;

use Queue;
use Illuminate\Bus\Dispatcher as IlluminateDispatcher;

class Dispatcher extends IlluminateDispatcher
{
	/**
	 * Push the command onto the given queue instance.
	 *
	 * @param  \Illuminate\Contracts\Queue\Queue  $queue
	 * @param  mixed  $command
	 * @return mixed
	 */
	protected function pushCommandToQueue($queue, $command)
	{
		$class = 'Ahead4\Bus\CallQueuedHandler@call';
		$queueData = [
			'pipes'   => $this->pipes,
			'command' => serialize($command),
		];

        if (isset($command->queue, $command->delay)) {
            return $queue->laterOn($command->queue, $command->delay, $class, $queueData);
        }

        if (isset($command->queue)) {
            return $queue->pushOn($command->queue, $class, $queueData);
        }

        if (isset($command->delay)) {
            return $queue->later($command->delay, $class, $queueData);
        }

        return $queue->push($class, $queueData);
	}
}
