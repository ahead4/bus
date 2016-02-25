<?php namespace Ahead4\Bus;

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
		if (isset($command->queue, $command->delay)) {
			return $queue->laterOn($command->queue, $command->delay, $command);
		}

		if (isset($command->queue)) {
			return $queue->pushOn($command->queue, $command);
		}

		if (isset($command->delay)) {
			return $queue->later($command->delay, $command);
		}

		return $queue->push('Ahead4\Bus\CallQueuedHandler@call', [
			'pipes'   => $this->pipes,
			'command' => serialize($command),
		]);
	}
}
