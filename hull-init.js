Hull.init(window.HullConfig.hull);
Hull.on('hull.login', function (me) {
  jQuery.ajax({
    type: 'POST',
    url: window.HullConfig.loginUrl,
    data: {
      user_email: me.get('email'),
      user_login: me.get('id')
    }
  }).then(function () {
    window.location.reload();
  });
});
Hull.on('hull.logout', function (me) {
  window.location = window.HullConfig.logoutUrl;
});
