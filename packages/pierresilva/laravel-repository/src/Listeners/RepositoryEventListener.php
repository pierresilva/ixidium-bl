<?php

namespace pierresilva\Repository\Listeners;

use pierresilva\Repository\Contracts\Repository;

class RepositoryEventListener
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen('*.entity.created', __CLASS__.'@entityCreated');
        $events->listen('*.entity.updated', __CLASS__.'@entityUpdated');
        $events->listen('*.entity.deleted', __CLASS__.'@entityDeleted');
    }

    /**
     * Listen for the *.entity.created event.
     *
     * @param $event
     * @param $data
     * @return void
     */
    public function entityCreated($event, $data)
    {
        $repository = $data[0];

        $repository->flushCache();
    }

    /**
     * Listen for the *.entity.updated event.
     *
     * @param $event
     * @param $data
     * @return void
     */
    public function entityUpdated($event, $data)
    {
        $repository = $data[0];

        $repository->flushCache();
    }

    /**
     * Listen for the *.entity.deleted event.
     *
     * @param $event
     * @param $data
     * @return void
     */
    public function entityDeleted($event, $data)
    {
        $repository = $data[0];

        $repository->flushCache();
    }
}
