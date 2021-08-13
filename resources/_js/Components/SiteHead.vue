<script>
   export default {
      props: {
         title: String,
      },
   }
</script>

<template>
   <inertia-head :title="title ? `${title} - ` : ''">
      <slot />
   </inertia-head>
</template>
