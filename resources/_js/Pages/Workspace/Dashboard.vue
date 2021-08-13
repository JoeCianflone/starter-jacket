<script>
   import {DTF} from '@data/Globals'

   export default {
      components: {},
      props: {
         events: Array,
         messages: Array,
         users: Array,
      },

      data: () => ({
         sending: false,
         DTF: DTF,
         form: this.$inertia.form({
            name: null,
         }),
      }),
   }
</script>

<template>
   <div>
      <h1>Hi There</h1>
      <form @submit.prevent="form.post()"> </form>
   </div>
</template>
