<?php
namespace Dash\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateResource extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'dash:make-resource {Resource}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate New Resource';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	protected function getResourceStub() {
		return file_get_contents(__DIR__ . '/myStubs/resource.stub');
	}

	public function convention_name($string) {
		if (substr($string, -3) == 'ies') {
			$conv = substr($string, 0, -3) . 'y';
		} elseif (substr($string, -1) == 's') {
			$conv = substr($string, 0, -1);
		} else {
			$conv = $string;
		}
		return ucfirst($conv);
	}

	protected function generate($namespace = 'App\Dash\Resources', $name) {
		$template = str_replace(
			['{{namespace}}', '{{resourcename}}', '{{model}}'],
			[$namespace, $name, $this->convention_name($name)],
			$this->getResourceStub()
		);

		$path = app_path("Dash/Resources");
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
		$Resource = $this->argument('Resource');
		$this->generate('App\Dash\Resources', $Resource);
		$this->info($Resource . ' Resource generated');

		return 0;
	}
}
