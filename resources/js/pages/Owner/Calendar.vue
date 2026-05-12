<script setup lang="ts">
import OwnerLayout from '@/layouts/OwnerLayout.vue';
import { router } from '@inertiajs/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';
import FullCalendar from '@fullcalendar/vue3';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const calendarOptions = ref({
    plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay',
    },
    height: 'auto',
    nowIndicator: true,
    businessHours: {
        daysOfWeek: [1, 2, 3, 4, 5],
        startTime: '07:00',
        endTime: '18:00',
    },
    slotMinTime: '06:00:00',
    slotMaxTime: '20:00:00',
    eventSources: [
        {
            url: '/owner/calendar/events',
            method: 'GET',
            extraParams: {},
            failure: () => {
                console.error('Failed to load calendar events');
            },
        },
    ],
    eventClick: (info: { event: { url: string }; jsEvent: Event }) => {
        info.jsEvent.preventDefault();
        if (info.event.url) {
            router.visit(info.event.url);
        }
    },
    eventTimeFormat: {
        hour: 'numeric',
        minute: '2-digit',
        meridiem: 'short',
    },
    dayMaxEvents: true,
});
</script>

<template>
    <OwnerLayout title="Calendar">
        <Head title="Calendar" />

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-800">Calendar</h2>
            <Link
                href="/owner/jobs/create"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700"
            >
                + New Job
            </Link>
        </div>

        <div class="rounded-xl bg-white p-4 shadow">
            <FullCalendar :options="calendarOptions" />
        </div>
    </OwnerLayout>
</template>

<style>
/* Ensure FullCalendar renders cleanly inside Tailwind's reset */
.fc .fc-button {
    border: 1px solid rgb(226 232 240);
    border-radius: 0.25rem;
    background-color: rgb(255 255 255);
    color: rgb(51 65 85);
    font-size: 0.875rem;
    line-height: 1.25rem;
    box-shadow: 0 1px 2px 0 rgb(15 23 42 / 0.05);
}
.fc .fc-button-primary {
    border-color: rgb(30 41 59);
    background-color: rgb(30 41 59);
    color: rgb(255 255 255);
}
.fc .fc-button-primary:not(:disabled):active,
.fc .fc-button-primary.fc-button-active {
    border-color: rgb(15 23 42);
    background-color: rgb(15 23 42);
}
.fc .fc-toolbar-title {
    color: rgb(30 41 59);
    font-size: 1.125rem;
    font-weight: 600;
    line-height: 1.75rem;
}
.fc-theme-standard td,
.fc-theme-standard th {
    border-color: rgb(241 245 249);
}
.fc .fc-daygrid-day.fc-day-today,
.fc .fc-timegrid-col.fc-day-today {
    background-color: rgb(239 246 255);
}
.fc .fc-button:hover {
    background-color: rgb(248 250 252);
}
.fc .fc-button:focus {
    outline: none;
}
.fc .fc-button-primary:hover {
    background-color: rgb(51 65 85);
}
</style>
