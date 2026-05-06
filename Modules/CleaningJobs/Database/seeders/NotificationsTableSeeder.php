<?php

namespace Workdo\JobBoard\Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // email notification
        $notifications = [
            'User Invited',
            'Project Assigned',
        ];

        $permissions = [
            'jobboard manage',
            'jobboard manage',
        ];

        foreach ($notifications as $key => $n) {
            $ntfy = Notification::where('action', $n)->where('type', 'mail')->where('module', 'JobBoard')->count();
            if ($ntfy == 0) {
                $new = new Notification();
                $new->action = $n;
                $new->status = 'on';
                $new->permissions = $permissions[$key];
                $new->module = 'JobBoard';
                $new->type = 'mail';
                $new->save();
            }
        }

    }
}
