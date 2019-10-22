<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\ThirdParties\Models\ThirThirdParty;

class GenerateApiDoc extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'api:docs
                            {version? : The api version.}';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Generate API documents';

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
   * @return mixed
   */
  public function handle()
  {
    $this->call('env:set', [
      'BASELINE_generating_documentation',
      true
    ]);

    $version = $this->argument('version');

    $routePrefix = "api/*";

    if ($version) {
      $routePrefix = "api/{$version}/*";
    }

    $this->call('api:generate', [
      '--routePrefix' => $routePrefix,
      '--force' => true,
      '--output' => 'public/docs/laravel',
      // '--authGuard' => 'api',
      '--actAsUserId' => 1,
      // '--header' => 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9yZW5vdmEudGVzdFwvYXBpXC90cmFuc3ZlcnNhbC1zZWN1cml0eVwvbG9naW4iLCJpYXQiOjE1NjQ3NjM2MDEsImV4cCI6MTU2NTk3MzIwMSwibmJmIjoxNTY0NzYzNjAxLCJqdGkiOiJDSVNtM0kycng0bDl0VEVlIiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIiwiZmlyc3RfbmFtZSI6IlBpZXJyZSBNaWNoZWwiLCJsYXN0X25hbWUiOiJTaWx2YSBQZXJleiIsIm5hbWUiOiJyZW5vdmEucHJ1ZWJhIiwiaWRlbnRpZmljYXRpb24iOiI3NzA4MTcwIiwiaWQiOjEwODg0fQ.v9bCflUBevwMEybXYstvjgXxbN4q9pqI_I8jy7bCfRE',
      // '--noResponseCalls' => true,
    ]);

    $file = __DIR__ . '/../../../public/docs/laravel/index.html';
    if (file_exists($file)) {
      $html = file_get_contents($file);

      $html = str_replace('\n', "\n", $html);
      $html = str_replace('"s{', '{', $html);
      $html = str_replace('}e"', '}', $html);
      $html = str_replace('\"', '"', $html);
      $html = str_replace('\\', '', $html);

      file_put_contents($file, $html);
    }
    $this->call('env:set', ['BASELINE_generating_documentation', false]);
  }
}
