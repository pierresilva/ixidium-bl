<?php
namespace pierresilva\Websockets;

use Illuminate\Support\Facades\Event;

class LaravelEventPublisher implements EventPublisherInterface{

	public function fire($event, $data = [], $stopIfDataReturned = true){
		return Event::fire($event, $data, $stopIfDataReturned);
	}

}
