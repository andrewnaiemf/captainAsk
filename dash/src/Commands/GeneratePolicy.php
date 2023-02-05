<?php
namespace Dash\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePolicy extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'dash:make-policy {policy}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate New Policy';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	protected function getResourceStub() {
		return file_get_contents(__DIR__ . '/myStubs/Policy.stub');
	}

	protected function generate($namespace = 'App\Policies', $name, $resource) {
		$template = str_replace(
			['{{namespace}}', '{{name}}', '{{resource}}'],
			[$namespace, $name, $resource],
			$this->getResourceStub()
		);

		$path = app_path("Policies");
		if (!File::exists($path)) {
			File::makeDirectory($path, 0775, true);
		}

		file_put_contents($path . '/' . $name . '.php', $template);
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		$policy = $this->argument('policy');

		$this->generate('App\Policies', $policy, ucfirst(str_replace('policy', 's', strtolower($policy))));

		$this->info($policy . ' Policy generated');

		return 0;
	}
}
