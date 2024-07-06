<template>
    <div>
        <ul class="flex gap-4">
            <li>
                <div class="max-w-sm p-6 bg-white border border-gray-300 rounded-lg">
                    <h2 class="mb-6 text-2xl font-roboto font-semibold text-gray-800">Math Study Group</h2>
                    <div class="flex items-end text-gray-700">
                        <div class="flex flex-col flex-none mr-6">
                            <span class="text-sm font-medium mb-1">Participants</span>
                            <span class="text-md font-bold">5</span>
                        </div>
                        <div class="flex flex-col flex-none mr-8">
                            <span class="text-sm font-medium mb-1">Next meeting</span>
                            <time class="text-md font-bold" datetime="2024-07-11T14:30">2024/07/11 14:30</time>
                        </div>
                        <div class="w-1/3">
                            <button type="button"
                                class="p-2 hover:bg-blue-100 rounded-full flex justify-center items-center focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                                <svg class="h-6 w-auto text-gray-600" xmlns="http://www.w3.org/2000/svg" width="32"
                                    height="32" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="m14 18l-1.4-1.45L16.15 13H4v-2h12.15L12.6 7.45L14 6l6 6z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="max-w-sm p-6 bg-white border border-gray-300 rounded-lg">
                    <h2 class="mb-6 text-2xl font-roboto font-semibold text-gray-800">Math Study Group</h2>
                    <div class="flex items-end text-gray-700">
                        <div class="flex flex-col flex-none mr-6">
                            <span class="text-sm font-medium mb-1">Participants</span>
                            <span class="text-md font-bold">5</span>
                        </div>
                        <div class="flex flex-col flex-none mr-8">
                            <span class="text-sm font-medium mb-1">Next meeting</span>
                            <time class="text-md font-bold" datetime="2024-07-11T14:30">2024/07/11 14:30</time>
                        </div>
                        <div class="w-1/3">
                            <button type="button"
                                class="p-2 hover:bg-blue-100 rounded-full flex justify-center items-center focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">
                                <svg class="h-6 w-auto text-gray-600" xmlns="http://www.w3.org/2000/svg" width="32"
                                    height="32" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="m14 18l-1.4-1.45L16.15 13H4v-2h12.15L12.6 7.45L14 6l6 6z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
definePageMeta({
    layout: 'dashboard'
})
</script>
