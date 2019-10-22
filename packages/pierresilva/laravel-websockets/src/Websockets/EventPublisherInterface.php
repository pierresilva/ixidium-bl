<?php
namespace pierresilva\Websockets;

interface EventPublisherInterface{

	public function fire($event, $data = [], $stopIfDataReturned = true);

}
