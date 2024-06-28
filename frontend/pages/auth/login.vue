<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white">
            <div class="flex justify-center mb-6">
                <img class="w-auto h-10" src="/studypeer.png" alt="logo">
            </div>
            <h2 class="mb-6 text-2xl font-semibold text-center text-gray-900">
                Welcome Back!
            </h2>
            <p class="text-center text-gray-900 font-opensans">
                Log in to access your study groups and sessions
            </p>
            <form class="space-y-6 font-opensans mt-8">
                <div class="mb-4">
                    <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email address</label>
                    <input id="email" name="email" type="email" required
                        class="w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        placeholder="name@company.com">
                </div>
                <div>
                    <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox"
                            class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <label for="remember_me" class="block ml-2 text-sm text-gray-900">Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-blue-600">Forgot your password?</a>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-primary border border-transparent rounded-md group hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Sign in
                    </button>
                </div>
            </form>
            <p class="mt-2 text-sm text-center text-gray-600">
                Don’t have an account? <NuxtLink to="/auth/signup" class="font-medium text-primary hover:text-primary">
                    Sign up</NuxtLink>
            </p>
        </div>
    </div>
</template>