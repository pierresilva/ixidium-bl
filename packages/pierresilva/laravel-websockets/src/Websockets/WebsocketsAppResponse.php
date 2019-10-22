<?php
namespace pierresilva\Websockets;

class WebsocketsAppResponse
{

	/**
	 * Formats an app.success event response.
	 * @param array $data
	 * @return object
	 */
	public function success($data = []){
		return $this->message('app.success',$data);
	}

	/**
	 * Formats an app.error event response.
	 * @param array $data
	 * @return object
	 */
	public function error($data = []){
		return $this->message('app.error',$data);
	}

	/**
	 * Formats an object response to later be sent to all connected clients via BrainSocketEventListener
	 * @param $event
	 * @param array $data
	 * @return object
	 */
	public function message($event, $data = []){
		$response = (object)array();
		$response->event = $event;
		$response->data = $data;
		return $response;
	}

}
