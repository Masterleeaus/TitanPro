<?php
namespace Modules\CleaningJobs\Services\AI;
class AgentToolRegistry { public function names(): array { return ["cleaning_jobs.lookup_availability","cleaning_jobs.estimate_job","cleaning_jobs.create_booking","cleaning_jobs.generate_checklist","cleaning_jobs.escalate_overdue_visit"]; } }
