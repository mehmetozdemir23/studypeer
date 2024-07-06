<template>
    <div>
        <h1 class="font-montserrat font-semibold text-lg">Welcome, Mehmet!</h1>
        <p class="font-roboto">Here's a summary of your recent activity and upcoming sessions.</p>
        <div class="flex gap-4 py-4 font-roboto">
            <div class="bg-blue-100 rounded-xl pl-4 pr-8 pt-4 pb-8 w-1/2">
                <h2 class="flex items-center font-bold text-primary mb-6">
                    <svg class="w-7 h-auto mr-1" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        viewBox="0 0 24 24">
                        <path fill="currentColor" d="M3.5 18.5L2 17l7.5-7.5l4 4l7.1-8L22 6.9l-8.5 9.6l-4-4z" />
                    </svg>Recent activity
                </h2>
                <ol class="relative border-s border-blue-300 ml-2">
                    <li class="mb-10 ms-6">
                        <span
                            class="absolute flex items-center justify-center w-2 h-2 bg-blue-600 rounded-full -start-1.5">
                        </span>
                        <div
                            class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex">
                            <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">just now</time>
                            <div class="text-sm font-normal text-gray-500">Bonnie moved <a href="#"
                                    class="font-semibold text-blue-600 hover:underline">Jese Leos</a>
                                to <span
                                    class="bg-gray-100 text-gray-800 text-xs font-normal me-2 px-2.5 py-0.5 rounded">Funny
                                    Group</span></div>
                        </div>
                    </li>
                    <li class="mb-10 ms-6">
                        <span
                            class="absolute flex items-center justify-center w-2 h-2 bg-blue-600 rounded-full -start-1.5">
                        </span>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="items-center justify-between mb-3 sm:flex">
                                <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">2 hours
                                    ago</time>
                                <div class="text-sm font-normal text-gray-500 lex">Thomas Lean
                                    commented on <a href="#"
                                        class="font-semibold text-gray-900 hover:underline">Flowbite
                                        Pro</a></div>
                            </div>
                            <div
                                class="p-3 text-xs italic font-normal text-gray-500 border border-gray-200 rounded-lg bg-gray-50">
                                Hi ya'll! I wanted to share a webinar zeroheight is having regarding how to best measure
                                your design system! This is the second session of our new webinar series on
                                #DesignSystems discussions where we'll be speaking about Measurement.</div>
                        </div>
                    </li>
                    <li class="ms-6">
                        <span
                            class="absolute flex items-center justify-center w-2 h-2 bg-blue-600 rounded-full -start-1.5">
                        </span>
                        <div
                            class="items-center justify-between p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:flex">
                            <time class="mb-1 text-xs font-normal text-gray-400 sm:order-last sm:mb-0">1 day ago</time>
                            <div class="text-sm font-normal text-gray-500 lex">Jese Leos has changed
                                <a href="#" class="font-semibold text-blue-600 hover:underline">Pricing
                                    page</a> task status to <span class="font-semibold text-gray-900">Finished</span>
                            </div>
                        </div>
                    </li>
                </ol>
            </div>
            <div class="bg-blue-100 rounded-xl pl-4 pr-8 pt-4 pb-8 w-1/2">
                <h2 class="flex items-center font-bold text-primary mb-6">
                    <svg class="w-7 h-auto mr-2" xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M15 22v-2h4V10H5v4H3V6q0-.825.588-1.412T5 4h1V2h2v2h8V2h2v2h1q.825 0 1.413.588T21 6v14q0 .825-.587 1.413T19 22zm-7 2l-1.4-1.4L9.175 20H1v-2h8.175L6.6 15.4L8 14l5 5zM5 8h14V6H5zm0 0V6z" />
                    </svg>Upcoming sessions
                </h2>

                <ol class="flex flex-col space-y-6">
                    <li class="flex items-center">
                        <span
                            class="flex items-center justify-center py-1 px-1.5 mr-2 text-sm text-white bg-blue-600 rounded-full">
                            <time datetime="14:30">14:30</time>
                        </span>
                        <p class="text-base font-normal text-gray-600">Algebra Revision - June 25, 2024, 3:00 PM
                        </p>
                    </li>
                    <li class="flex items-center">
                        <span
                            class="flex items-center justify-center py-1 px-1.5 mr-2 text-sm text-white bg-blue-600 rounded-full">
                            <time datetime="14:30">14:30</time>
                        </span>
                        <p class="text-base font-normal text-gray-600">Algebra Revision - June 25, 2024, 3:00 PM
                        </p>
                    </li>
                    <li class="flex items-center">
                        <span
                            class="flex items-center justify-center py-1 px-1.5 mr-2 text-sm text-white bg-blue-600 rounded-full">
                            <time datetime="14:30">14:30</time>
                        </span>
                        <p class="text-base font-normal text-gray-600">Algebra Revision - June 25, 2024, 3:00 PM
                        </p>
                    </li>
                </ol>


            </div>
        </div>
    </div>
</template>
<script setup>
definePageMeta({
    layout: 'dashboard'
})
</script>