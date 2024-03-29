<?php
namespace pierresilva\Websockets;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Websockets extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'websockets:start';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Starts Websockets and the Ratchet WebSocket server to start running event-driven apps with Laravel.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{

		$port = $this->option('port');

		$server = new WebsocketsServer();
		$server->start($port);
		$this->info('WebSocket server started on port:'.$port);
		$server->run();

	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			[
        'port',
        null,
        InputOption::VALUE_OPTIONAL,
        'The port you want the Websockets server to run on (default: 8080)',
        '8080'
      ],
    ];
	}

}
