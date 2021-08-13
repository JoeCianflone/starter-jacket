<script>
   export default {
      components: {},

      methods: {
         url() {
            return location.pathname.substr(1)
         },
      },
   }
</script>

<template>
   <section class="page">
      <SiteHead />
      <portal-target name="dropdown" slim />
      <portal-target name="overlay" slim />
      <slot></slot>
   </section>
</template>

<style lang="css" scoped>
   .page {
      position: relative;
      height: 100%;
      width: 100vw;
   }
</style>
