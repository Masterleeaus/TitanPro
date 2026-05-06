<?php
namespace Modules\CallingAgent\Repositories;

use Modules\CallingAgent\Models\CallingAgent;
use Modules\CallingAgent\Interfaces\CallingAgentRepositoryInterface;

/**
 * Concrete repository for accessing CallingAgent records.
 *
 * This class encapsulates data access for the primary CallingAgent model.
 */
class CallingAgentRepository implements CallingAgentRepositoryInterface
{
    /**
     * Find a CallingAgent by its primary key.
     *
     * @param int $id
     * @return CallingAgent|null
     */
    public function find(int $id): ?CallingAgent
    {
        return CallingAgent::find($id);
    }
}
