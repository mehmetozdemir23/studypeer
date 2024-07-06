<template>
    <div class="flex h-full">
        <div class="w-1/3 border-r border-gray-300 overflow-y-auto">
            <button
                class="bg-green-600 text-white font-semibold text-base py-2 px-4 mx-6 my-4 rounded hover:bg-green-600">New
                Message</button>
            <div class="px-6">
                <input type="text" placeholder="Search conversations"
                    class="w-full py-2 px-4 border border-gray-300 rounded mb-4">
            </div>
            <ul class="list-none px-6">
                <li class="flex items-center p-4 cursor-pointer hover:bg-gray-100">
                    <img src="https://i.pravatar.cc/50" alt="John Doe" class="w-12 h-12 rounded-full mr-4">
                    <div class="flex-1">
                        <div class="font-bold text-lg">John Doe</div>
                        <div class="text-gray-600">Sure, I'll send the notes later today.</div>
                    </div>
                    <div class="text-gray-500 text-sm">2:35 PM</div>
                </li>
                <li class="flex items-center p-4 cursor-pointer hover:bg-gray-100">
                    <img src="https://i.pravatar.cc/50" alt="Calculus Study Group" class="w-12 h-12 rounded-full mr-4">
                    <div class="flex-1">
                        <div class="font-bold text-lg">Calculus Study Group</div>
                        <div class="text-gray-600">Reminder: meeting at 5 PM today.</div>
                    </div>
                    <div class="text-gray-500 text-sm">1:15 PM</div>
                </li>
            </ul>
        </div>
        <div class="w-2/3 flex flex-col justify-between">
            <div class="flex items-center p-4 border-b border-gray-300">
                <img src="https://i.pravatar.cc/50" alt="John Doe" class="w-12 h-12 rounded-full mr-4">
                <div class="flex-1 font-bold text-lg">John Doe</div>
                <div class="text-gray-500 cursor-pointer text-xl">⚙️</div>
            </div>
            <div class="flex-1 p-4 overflow-y-auto">
                <div class="mb-4">
                    <div class="font-bold text-base">John Doe</div>
                    <div class="text-sm text-gray-500">2:35 PM</div>
                    <div class="text-base mt-2">Sure, I'll send the notes later today.</div>
                </div>
                <div class="mb-4">
                    <div class="font-bold text-base">You</div>
                    <div class="text-sm text-gray-500">2:30 PM</div>
                    <div class="text-base mt-2">Can you send me the notes for today’s lecture?</div>
                </div>
            </div>
            <div class="p-4 border-t border-gray-300 flex">
                <input type="text" placeholder="Type a message"
                    class="flex-1 py-2 px-4 border border-gray-300 rounded mr-4">
                <button
                    class="bg-blue-500 text-white font-semibold text-base py-2 px-4 rounded hover:bg-blue-600">Send</button>
            </div>
        </div>
    </div>
</template>
<script setup>
definePageMeta({ layout: 'dashboard' })
</script>