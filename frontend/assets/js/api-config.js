(function () {
  'use strict';
  // Override STC_API_BASE_URL before this script when deploying to different ports.
  const base=new URL(window.STC_API_BASE_URL||'./',location.href);
  if(!base.pathname.endsWith('/'))base.pathname+='/';
  if(!window.STC_API_BASE_URL&&location.port==='3000')base.port='8001';
  window.STC_API={
    base:base.href.replace(/\/$/,''),
    url:function(path){return new URL('api/'+path.replace(/^\/+/ ,''),base).href;},
    link:function(value){
      if(location.port!=='3000'||/^(?:https?:)?\/\//i.test(value)||value.startsWith('#'))return value;
      const url=new URL(value,location.href);
      if(/\/(?:login|logout|forgot-password|reset-password|entrepreneurs)\.php$/.test(url.pathname))return new URL(value,base).href;
      return value.replace(/\.php(?=[?#]|$)/,'.html');
    }
  };
})();
