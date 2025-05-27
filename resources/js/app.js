import './bootstrap';

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import { ZiggyVue } from "../../vendor/tightenco/ziggy"
import Main from './Layouts/Main.vue';
import { setThemeOnLoad } from './theme';

createInertiaApp({
    title: (title) => `My App ${title}`,
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]

    if (
        !name.startsWith("Admin") &&
        !name.startsWith("Donor") &&
        !name.startsWith("Recipient") &&
        !name.startsWith("Hospital") &&
        !name.startsWith("Organization") &&
        !name.endsWith("Edit") &&
        !name.endsWith("Create")
    ) {
        page.default.layout = page.default.layout || Main;
    }
    return page;

  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .component("Head", Head)
      .component("Link", Link)
      .mount(el)
  },
  progress: {
    color: "#fff",
    includeCSS: true,
    showSpinner: true,
  }
})

setThemeOnLoad()
