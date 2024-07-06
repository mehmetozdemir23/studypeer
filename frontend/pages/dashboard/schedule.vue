<template>
    <div class="h-full">
        <Calendar expanded borderless :attributes="attributes">
            <template #day-content="{ day }">
                <div class="h-full flex flex-col">
                    <span>{{ day.date.getDate() }}</span>
                    <div v-if="hasEvent(day)"
                        class="mt-auto font-semibold text-xs text-white w-max bg-primary px-1.5 py-1 rounded-full">
                        {{ getEventData(day) }}
                    </div>
                </div>
            </template>
        </Calendar>
    </div>
</template>
<script setup>
import { Calendar } from 'v-calendar'
import 'v-calendar/style.css'

definePageMeta({
    layout: 'dashboard'
})

const attributes = [{
    dates: new Date(),
    customData: 'text'
}]

const events = ref({
    '2024-06-29': 'Event 1',
    '2024-06-30': 'Event 2',
})

const hasEvent = (day) => {
    const dateString = day.date.toISOString().split('T')[0];
    return !!events.value[dateString];
}

const getEventData = (day) => {
    const dateString = day.date.toISOString().split('T')[0];
    return events.value[dateString] || '';
}
</script>
<style>
.vc-day {
    padding: 8px;
    min-height: 85px;
    border-right: 0;
    border-left: solid #ccc 1px;
    border-top: solid #ccc 1px;
    border-bottom: 0;
    font-size: 0.9rem;
}

.vc-day-content {
    width: 100%;
    height: 100%;
    border-radius: 0;
}
</style>