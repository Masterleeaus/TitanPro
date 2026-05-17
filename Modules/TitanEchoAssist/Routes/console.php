<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('chatbot:portal:check-overdue-invoices')->dailyAt('09:00')->timezone(config('app.timezone', 'UTC'));
Schedule::command('chatbot:portal:send-visit-reminders')->dailyAt('18:00')->timezone(config('app.timezone', 'UTC'));
