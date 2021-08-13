import Vue from 'vue'
import PortalVue from 'portal-vue'
import {createInertiaApp} from '@inertiajs/inertia-vue'
import {InertiaProgress} from '@inertiajs/progress/src'
import store from '@/store'
import SiteHead from '@components/SiteHead'
import Layout from '@templates/Layout.vue'

Vue.config.productionTip = false
Vue.mixin({methods: {route: window.route}})
Vue.use(PortalVue)

Vue.component('SiteHead', SiteHead)

InertiaProgress.init()

createInertiaApp({
   resolve: (name) =>
      import(`@/${name}`).then(({default: page}) => {
         page.layout = page.layout === undefined ? Layout : page.layout
         return page
      }),
   setup({el, app, props}) {
      new Vue({
         store,
         render: (h) => h(app, props),
      }).$mount(el)
   },
})
