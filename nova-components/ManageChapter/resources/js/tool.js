Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'manage-chapter',
      path: '/manage-chapter/:courseId/:courseTitle',
      component: require('./components/Tool'),
    },
  ])
})
