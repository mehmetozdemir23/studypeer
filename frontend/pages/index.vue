<template>
    <div>
        <nav class="fixed left-0 right-0 py-6 px-6 sm:py-8 sm:px-16 flex justify-between bg-white">
            <img src="/studypeer.png" alt="studypeer" class="h-6 sm:h-8 w-auto" />
            <ul class="hidden sm:flex space-x-4 sm:space-x-8 font-opensans">
                <li>Home</li>
                <li>Features</li>
                <li>Pricing</li>
                <li>Contact</li>
                <li>Join</li>
            </ul>
            <div class="sm:hidden">
                <button @click="toggleMenu" class="text-xl">
                    ☰
                </button>
            </div>
        </nav>
        <div v-if="menuOpen" class="sm:hidden bg-white absolute top-16 left-0 right-0 py-4 px-4">
            <ul class="flex flex-col space-y-4 font-opensans">
                <li>Home</li>
                <li>Features</li>
                <li>Pricing</li>
                <li>Contact</li>
                <li>Join Us</li>
            </ul>
        </div>
        <main class="pt-24 sm:pt-44 mx-auto flex flex-col min-h-screen">
            <section class="flex flex-col sm:flex-row justify-center items-center space-x-0 sm:space-x-20 sm:px-16">
                <section class="mb-4 text-center sm:text-left">
                    <h1 class="font-hanken font-extrabold text-3xl sm:text-5xl md:text-6xl mb-3">
                        Collaborative learning <br />made easy
                    </h1>
                    <p class="font-opensans text-md sm:text-xl mb-8 ml-0 sm:ml-1">
                        Join study groups, schedule sessions, <br />and chat
                        with peers in real-time.
                    </p>
                    <a href="/"
                        class="text-md sm:text-lg font-semibold font-nunito py-2.5 px-5 -ml-0 sm:-ml-1 text-white bg-primary rounded-full">
                        Get started
                    </a>
                </section>
                <section>
                    <img src="/workchat.svg" alt="study group" class="w-full sm:w-auto h-48 sm:h-72" />
                </section>
            </section>
            <section
                class="flex flex-col sm:flex-row justify-center space-y-12 sm:space-y-0 sm:space-x-16 md:space-x-20 mx-auto py-12 sm:py-24 px-16 sm:max-w-4xl">
                <div class="flex flex-col items-center sm:items-start">
                    <div
                        class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-blue-100 lg:h-16 lg:w-16">
                        <svg class="w-6 h-auto text-blue-600 lg:w-7 mb-1" xmlns="http://www.w3.org/2000/svg" width="32"
                            height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M0 18v-1.575q0-1.075 1.1-1.75T4 14q.325 0 .625.013t.575.062q-.35.525-.525 1.1t-.175 1.2V18zm6 0v-1.625q0-.8.438-1.463t1.237-1.162T9.588 13T12 12.75q1.325 0 2.438.25t1.912.75t1.225 1.163t.425 1.462V18zm13.5 0v-1.625q0-.65-.162-1.225t-.488-1.075q.275-.05.563-.062T20 14q1.8 0 2.9.663t1.1 1.762V18zM4 13q-.825 0-1.412-.587T2 11q0-.85.588-1.425T4 9q.85 0 1.425.575T6 11q0 .825-.575 1.413T4 13m16 0q-.825 0-1.412-.587T18 11q0-.85.588-1.425T20 9q.85 0 1.425.575T22 11q0 .825-.575 1.413T20 13m-8-1q-1.25 0-2.125-.875T9 9q0-1.275.875-2.137T12 6q1.275 0 2.138.863T15 9q0 1.25-.862 2.125T12 12" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg sm:text-xl font-bold">Create Study Groups</h3>
                    <p class="text-gray-500 text-center sm:text-left">Form or join study groups tailored to your
                        subjects and interests.</p>
                </div>
                <div class="flex flex-col items-center sm:items-start">
                    <div
                        class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-blue-100 lg:h-16 lg:w-16">
                        <svg class="w-6 h-auto text-blue-600 lg:w-7 mb-0.5 mr-0.5" xmlns="http://www.w3.org/2000/svg"
                            width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5 22q-.825 0-1.412-.587T3 20V6q0-.825.588-1.412T5 4h1V2h2v2h8V2h2v2h1q.825 0 1.413.588T21 6v14q0 .825-.587 1.413T19 22zm0-2h14V10H5zM5 8h14V6H5zm0 0V6zm7 6q-.425 0-.712-.288T11 13t.288-.712T12 12t.713.288T13 13t-.288.713T12 14m-4 0q-.425 0-.712-.288T7 13t.288-.712T8 12t.713.288T9 13t-.288.713T8 14m8 0q-.425 0-.712-.288T15 13t.288-.712T16 12t.713.288T17 13t-.288.713T16 14m-4 4q-.425 0-.712-.288T11 17t.288-.712T12 16t.713.288T13 17t-.288.713T12 18m-4 0q-.425 0-.712-.288T7 17t.288-.712T8 16t.713.288T9 17t-.288.713T8 18m8 0q-.425 0-.712-.288T15 17t.288-.712T16 16t.713.288T17 17t-.288.713T16 18" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg sm:text-xl font-bold">Schedule Sessions</h3>
                    <p class="text-gray-500 text-center sm:text-left">Easily schedule and manage study sessions with
                        your groups.</p>
                </div>
                <div class="flex flex-col items-center sm:items-start">
                    <div
                        class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-blue-100 lg:h-16 lg:w-16">
                        <svg class="w-6 h-auto text-blue-600 lg:w-7" xmlns="http://www.w3.org/2000/svg" width="32"
                            height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M2 22V4q0-.825.588-1.412T4 2h16q.825 0 1.413.588T22 4v12q0 .825-.587 1.413T20 18H6zm3.15-6H20V4H4v13.125zM4 16V4z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg sm:text-xl font-bold">Real-Time Chat</h3>
                    <p class="text-gray-500 text-center sm:text-left">Communicate instantly with group members using our
                        real-time chat.</p>
                </div>
            </section>
        </main>
        <footer class="mt-auto py-4 px-4 text-center text-sm sm:text-md">
            <a href="#terms">Terms of Service</a> |
            <a href="#privacy">Privacy Policy</a> |
            <a href="https://www.facebook.com" target="_blank">Facebook</a> |
            <a href="https://www.twitter.com" target="_blank">Twitter</a> |
            <a href="https://www.linkedin.com" target="_blank">LinkedIn</a>
            <p>© 2024 StudyPeer. All rights reserved.</p>
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            menuOpen: false,
        };
    },
    methods: {
        toggleMenu() {
            this.menuOpen = !this.menuOpen;
        },
    },
};
</script>

<style scoped>
/* Add any additional custom styles here */
</style>
