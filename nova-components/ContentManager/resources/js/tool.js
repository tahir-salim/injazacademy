Nova.booting((Vue, router, store) => {
  router.addRoutes([
    
    {
      name: 'content-manager',
      path: '/content-manager/:programId',
      component: require('./components/Tool'),
    },
  ])
})
