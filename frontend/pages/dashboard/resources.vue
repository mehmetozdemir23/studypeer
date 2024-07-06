<template>
    <div class="relative overflow-x-auto h-full pt-1">
        <ul class="flex mb-4 text-gray-500 text-sm font-semibold space-x-2">
            <li class="px-5 pt-1 pb-1.5 rounded-full bg-primary text-white">all</li>
            <li class="px-5 pt-1 pb-1.5 rounded-full border border-gray-300">notes</li>
            <li class="px-5 pt-1 pb-1.5 rounded-full border border-gray-300">videos</li>
            <li class="px-5 pt-1 pb-1.5 rounded-full border border-gray-300">links</li>
        </ul>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 divide-y divide-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Title
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Session
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Subject
                    </th>
                    <th scope="col">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        Apple MacBook Pro 17"
                    </th>
                    <td class="px-6 py-4">
                        Silver
                    </td>
                    <td class="px-6 py-4">
                        Laptop
                    </td>
                    <td>
                        <div class="relative flex justify-end">
                            <button type="button" @click="toggleMenu">
                                <svg class="h-6 w-auto" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>
                            </button>
                            <ul v-if="menuOpen"
                                class="z-50 absolute right-0 -bottom-4 w-48 translate-y-full divide-y text-gray-800 flex flex-col rounded-lg border border-gray-300 shadow-lg shadow-gray-200">
                                <li class="flex items-center p-3">
                                    <svg class="h-4 w-auto mr-1" xmlns="http://www.w3.org/2000/svg" width="32"
                                        height="32" viewBox="0 0 16 16">
                                        <path fill="currentColor"
                                            d="M2.984 8.625v.003a.5.5 0 0 1-.612.355c-.431-.114-.355-.611-.355-.611l.018-.062s.026-.084.047-.145a6.7 6.7 0 0 1 1.117-1.982C4.096 5.089 5.605 4 8 4s3.904 1.089 4.802 2.183a6.7 6.7 0 0 1 1.117 1.982a4 4 0 0 1 .06.187l.003.013v.004l.001.002a.5.5 0 0 1-.966.258l-.001-.004l-.008-.025l-.035-.109a5.7 5.7 0 0 0-.945-1.674C11.286 5.912 10.045 5 8 5s-3.285.912-4.028 1.817a5.7 5.7 0 0 0-.945 1.674l-.035.109zM5.5 9.5a2.5 2.5 0 1 1 5 0a2.5 2.5 0 0 1-5 0" />
                                    </svg>View
                                </li>
                                <li class="flex items-center p-3">
                                    <svg class="h-4 w-auto mr-1" xmlns="http://www.w3.org/2000/svg" width="32"
                                        height="32" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="m12 16l-5-5l1.4-1.45l2.6 2.6V4h2v8.15l2.6-2.6L17 11zm-6 4q-.825 0-1.412-.587T4 18v-3h2v3h12v-3h2v3q0 .825-.587 1.413T18 20z" />
                                    </svg>Download
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup>
definePageMeta({
    layout: 'dashboard'
})

const menuOpen = ref(false)

function toggleMenu() {
    menuOpen.value = !menuOpen.value
}
</script>