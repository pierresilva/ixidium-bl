<?php
namespace pierresilva\Websockets;

use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;

class WebsocketsServer
{
  protected $server;

  public function start($port)
  {
    $this->server = IoServer::factory(
      new HttpServer(
        new WsServer(
          new WebsocketsEventListener(
            new WebsocketsResponse(
              new LaravelEventPublisher()
            )
          )
        )
      ),
      $port
    );
  }

  public function run()
  {
    $this->server->run();
  }
}
