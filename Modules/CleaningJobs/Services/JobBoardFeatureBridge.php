<?php
namespace Modules\CleaningJobs\Services;
class JobBoardFeatureBridge
{
    public function enabledCapabilities(): array
    {
        return ['kanban_stages','tasks','sub_tasks','milestones','comments','task_files','project_files','activity_logs','bug_reports_as_quality_issues','client_projects','vendor_projects','project_invites','client_sharing','multi_language_labels'];
    }
    public function mapTaskToCleaningChecklist(array $task): array
    {
        return ['external_task_id'=>$task['id']??null,'name'=>$task['title']??$task['name']??'Cleaning task','description'=>$task['description']??null,'stage'=>$task['stage_id']??$task['stage']??null,'assigned_to'=>$task['assign_to']??$task['assigned_to']??null,'due_at'=>$task['due_date']??null];
    }
}
