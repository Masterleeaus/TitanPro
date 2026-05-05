<?php
namespace Modules\CallingAgent\Interfaces;

use Modules\CallingAgent\Models\CallingAgent;

/**
 * Interface for the CallingAgent repository.
 *
 * This contract defines the expected data access methods for the
 * primary CallingAgent model. Additional methods can be added as
 * needed to support more complex queries.
 */
interface CallingAgentRepositoryInterface
{
    /**
     * Retrieve a CallingAgent record by its primary key.
     *
     * @param int $id
     * @return CallingAgent|null
     */
    public function find(int $id): ?CallingAgent;
}
