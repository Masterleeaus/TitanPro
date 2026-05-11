<div
    class="space-y-5"
    x-data="{
        dispatchQueue: @js(collect($dispatchJobs ?? [
            ['id' => 2051, 'title' => 'End of Lease Refresh', 'window' => 'Today • 1:30 PM', 'site' => 'St Kilda Rd', 'priority' => 'High', 'duration' => '3.5h', 'fit' => 'Needs 2 cleaners + key pickup'],
            ['id' => 2052, 'title' => 'Weekly Office Reset', 'window' => 'Tomorrow • 7:00 AM', 'site' => 'South Wharf', 'priority' => 'Normal', 'duration' => '2h', 'fit' => 'Strong recurring fit'],
            ['id' => 2053, 'title' => 'Inspection Recovery', 'window' => 'Today • 4:00 PM', 'site' => 'South Yarra', 'priority' => 'Urgent', 'duration' => '1h', 'fit' => 'Inspector follow-up required'],
        ])->values()),
        teamMembers: @js(collect($dispatchCrew ?? [
            ['id' => 41, 'name' => 'Anna', 'role' => 'Lead Cleaner', 'status' => 'Free in 45 min', 'load' => 62, 'fit' => 'Best match', 'next' => 'Morning Deep Clean'],
            ['id' => 42, 'name' => 'Mike', 'role' => 'Inspector', 'status' => 'On active visit', 'load' => 88, 'fit' => 'Overloaded', 'next' => 'Quarterly Inspection'],
            ['id' => 43, 'name' => 'Sarah', 'role' => 'Cleaner', 'status' => 'Available now', 'load' => 28, 'fit' => 'Fast dispatch', 'next' => 'Ready for office reset'],
        ])->values()),
        todayStatus: @js(collect($todayJobs ?? [
            ['id' => 1042, 'status' => 'pending'],
            ['id' => 1043, 'status' => 'active'],
            ['id' => 1044, 'status' => 'complete'],
        ])->mapWithKeys(fn($job) => [$job['id'] ?? $job->id => $job['status'] ?? $job->status])),
        qaStatus: @js(collect($qaJobs ?? [
            ['title' => 'Deep Clean Handover', 'state' => 'Checklist incomplete'],
            ['title' => 'Inspection Follow-up', 'state' => 'Failed inspection'],
            ['title' => 'Office Reset', 'state' => 'Passed QA'],
        ])->mapWithKeys(fn($job) => [$job['title'] ?? $job->title => $job['state'] ?? $job->state])),
        teamStatusMap: @js(collect($teamStatus ?? [
            ['name' => 'Anna', 'status' => 'Working', 'utilisation' => 58],
            ['name' => 'Mike', 'status' => 'Break', 'utilisation' => 64],
            ['name' => 'Sarah', 'status' => 'Available', 'utilisation' => 26],
            ['name' => 'Luis', 'status' => 'Overloaded', 'utilisation' => 94],
        ])->mapWithKeys(fn($member) => [$member['name'] ?? $member->name => ['status' => $member['status'] ?? $member->status, 'utilisation' => $member['utilisation'] ?? $member->utilisation]])),
        activityFeed: [],
        startJob(id){ this.todayStatus[id] = 'active'; this.activityFeed.unshift(`Job #${id} started`); },
        completeJob(id){ this.todayStatus[id] = 'complete'; this.activityFeed.unshift(`Job #${id} completed and sent to QA`); },
        assignJob(jobId, memberName){
            const job = this.dispatchQueue.find(item => item.id === jobId);
            if (!job) return;
            this.dispatchQueue = this.dispatchQueue.filter(item => item.id !== jobId);
            this.teamStatusMap[memberName] = this.teamStatusMap[memberName] || { status: 'Available', utilisation: 0 };
            this.teamStatusMap[memberName].status = 'Assigned';
            this.teamStatusMap[memberName].utilisation = Math.min(100, (this.teamStatusMap[memberName].utilisation || 0) + 14);
            this.activityFeed.unshift(`${job.title} assigned to ${memberName}`);
        },
        approveQa(title){ this.qaStatus[title] = 'Passed QA'; this.activityFeed.unshift(`${title} approved in QA`); },
        reopenQa(title){ this.qaStatus[title] = 'Reopened'; this.activityFeed.unshift(`${title} reopened for fixes`); },
        rebalance(memberName){
            if (!this.teamStatusMap[memberName]) return;
            this.teamStatusMap[memberName].status = 'Rebalanced';
            this.teamStatusMap[memberName].utilisation = Math.max(35, (this.teamStatusMap[memberName].utilisation || 0) - 22);
            this.activityFeed.unshift(`${memberName} load rebalanced`);
        },
    }"
>
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1.15fr),minmax(0,0.85fr)]">
        @include('dashboard.partials.work-cards.today-card')
        @include('dashboard.partials.work-cards.dispatch-card')
    </div>

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr),minmax(0,1fr)]">
        @include('dashboard.partials.work-cards.qa-card')
        @include('dashboard.partials.work-cards.team-status-card')
    </div>

    <div class="rounded-2xl border border-border bg-background/55 p-4">
        <div class="flex items-center justify-between gap-3 max-sm:flex-col max-sm:items-start">
            <div>
                <p class="m-0 text-[11px] font-semibold uppercase tracking-[0.16em] text-foreground/45">@lang('Ops feed')</p>
                <h5 class="mt-1 text-sm font-semibold text-heading-foreground">@lang('Cross-card activity')</h5>
            </div>
            <p class="m-0 text-xs text-foreground/55">@lang('Assignment, execution, QA, and rebalance actions echo here immediately.')</p>
        </div>
        <div class="mt-4 grid gap-2">
            <template x-if="activityFeed.length === 0">
                <div class="rounded-xl border border-dashed border-border px-3 py-2 text-sm text-foreground/55">@lang('No actions yet. Use the cards above to drive live operations.') </div>
            </template>
            <template x-for="(event, index) in activityFeed.slice(0, 6)" :key="index">
                <div class="rounded-xl border border-border bg-background px-3 py-2 text-sm text-foreground/70" x-text="event"></div>
            </template>
        </div>
    </div>
</div>
