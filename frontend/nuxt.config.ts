// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: ["@nuxtjs/tailwindcss", "@nuxt/eslint", "@nuxtjs/google-fonts"],
  googleFonts: {
    families: {
      'Open Sans': true,
      'Hanken Grotesk': true,
      'Nunito': true,
      'Montserrat': true,
      'Roboto': true
    }
  }
})