Nova.booting((Vue, router, store) => {
  Vue.config.devtools = true
  router.addRoutes([
    {
      name: 'test-creator',
      path: '/test-creator',
      component: require('./components/Tool'),
    },
    {
      name: 'test-updater',
      path: '/test-creator/:id',
      component: require('./components/Tool'),
  },
  ])
})
