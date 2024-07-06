<template>
    <div class="h-full overflow-y-auto">
        <h1 class="font-montserrat font-bold text-3xl mb-8">Help & Support</h1>

        <!-- Resources -->
        <div class="mb-8">
            <h3 class="font-roboto font-medium text-xl mb-4">Resources</h3>
            <ul class="list-disc list-inside space-y-2">
                <li><a href="#" class="text-blue-500 hover:underline">Study Guides</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">Video Tutorials</a></li>
                <li><a href="#" class="text-blue-500 hover:underline">Exam Preparation Tips</a></li>
            </ul>
        </div>

        <!-- FAQs -->
        <div class="mb-8">
            <h3 class="font-roboto font-medium text-xl mb-4">Frequently Asked Questions (FAQs)</h3>
            <div class="space-y-4">
                <div class="bg-gray-100 rounded-lg p-4">
                    <h4 class="font-semibold text-lg mb-2">How do I join a study group?</h4>
                    <p class="text-gray-700">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4">
                    <h4 class="font-semibold text-lg mb-2">Can I create my own study group?</h4>
                    <p class="text-gray-700">Nulla facilisi. Fusce ut convallis purus.</p>
                </div>
                <div class="bg-gray-100 rounded-lg p-4">
                    <h4 class="font-semibold text-lg mb-2">How can I contact support?</h4>
                    <p class="text-gray-700">Etiam eget libero non elit dictum gravida.</p>
                </div>
            </div>
        </div>

        <!-- Contact Support Form -->
        <div>
            <h3 class="font-roboto font-medium text-xl mb-4">Contact Support</h3>
            <form class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" id="email" name="email"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm">
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message:</label>
                    <textarea id="message" name="message" rows="4"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 sm:text-sm"></textarea>
                </div>
                <button type="submit"
                    class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition duration-200">Submit</button>
            </form>
        </div>
    </div>
</template>

<script setup>
definePageMeta({
    layout: 'dashboard'
})
</script>