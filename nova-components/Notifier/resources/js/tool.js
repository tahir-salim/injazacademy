Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'notifier',
      path: '/notifier',
      component: require('./components/Tool'),
    },
  ])
})
