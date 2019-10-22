<?php

namespace pierresilva\Websockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;

class WebsocketsEventListener implements MessageComponentInterface
{
  protected $clients;
  protected $response;

  public static $instance;
  public static $connections;

  /**
   * WebsocketsEventListener constructor.
   * @param WebsocketsResponseInterface $response
   */
  public function __construct(WebsocketsResponseInterface $response)
  {
    $this->clients = new SplObjectStorage;
    $this->response = $response;

    self::$instance = $this;
    self::$connections = $this->clients;
  }

  /**
   * @param ConnectionInterface $conn
   */
  public function onOpen(ConnectionInterface $conn)
  {

    $this->clients->attach($conn);

    echo "Connection " . $conn->resourceId . " established! \n";

    $data = json_encode([
      'client' => [
        'event' => 'app.success',
        'data' => [
          'message' => 'Connection ' . $conn->resourceId . ' established!',
          'connection' => $conn->resourceId,
          'user_id' => $conn->resourceId,
        ],
      ]
    ], true);
    $message = $this->response->make($data);
    $conn->send($message);
  }

  /**
   * @param ConnectionInterface $from
   * @param string $msg
   */
  public function onMessage(ConnectionInterface $from, $msg)
  {
    $numRecv = count($this->clients) - 1;
    echo sprintf(
      'Connection %d sending message "%s" to %d other connection%s' . "\n",
      $from->resourceId,
      $msg,
      $numRecv,
      $numRecv == 1 ? '' : 's'
    );

    $message = $this->response->make($msg);
    foreach ($this->clients as $client) {
      $client->send($message);
    }
  }

  /**
   * @param ConnectionInterface $conn
   */
  public function onClose(ConnectionInterface $conn)
  {
    $this->clients->detach($conn);
    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  /**
   * @param ConnectionInterface $conn
   * @param \Exception $e
   */
  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
  }

  public static function get()
  {
    if (self::$instance === null) {
      self::$instance = new self(new WebsocketsResponse(new LaravelEventPublisher()));
    }
    return self::$instance;
  }

  public static function getClients()
  {
    return self::$connections;
  }
}
