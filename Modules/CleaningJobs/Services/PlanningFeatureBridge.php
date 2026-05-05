<?php
namespace Modules\CleaningJobs\Services;
class PlanningCoreFeatureBridge
{
    public function enabledCapabilities(): array
    {
        return ['projects_as_cleaning_contracts','project_status_pipeline','project_members','timesheets','timesheet_amounts','resource_allocations','resource_capacity','budget_tracking','budget_reports','resource_reports','time_reports'];
    }
    public function mapProjectToJob(array $project): array
    {
        return ['external_project_id'=>$project['id']??null,'title'=>$project['name']??$project['title']??'Cleaning Job','description'=>$project['description']??null,'priority'=>$project['priority']??'medium','budget_amount'=>$project['budget']??$project['budget_amount']??null,'starts_at'=>$project['start_date']??null,'ends_at'=>$project['end_date']??null];
    }
}
