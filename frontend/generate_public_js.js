const fs = require('fs');
const path = require('path');

try {
  const publicJsPath = path.join(__dirname, './src/assets/js/pages/public.js');
  const homeJsPath = path.join(__dirname, './src/assets/js/pages/home.js');

  let publicContent = fs.readFileSync(publicJsPath, 'utf-8');
  let homeContent = fs.readFileSync(homeJsPath, 'utf-8');

  let content = publicContent + '\n' + homeContent;
  
  // Remove imports and existing 't' definition
  content = content.replace(/import .*?;\n?/g, '');
  content = content.replace(/const t = .*?;\n?/g, '');  
  // Remove export keywords
  content = content.replace(/export function /g, 'function ');

  // Fix image paths for Angular
  content = content.replace(/'images\//g, "'assets/images/");
  content = content.replace(/"images\//g, '"assets/images/');
  content = content.replace(/`images\//g, '`assets/images/');

  // Wrap in an IIFE and expose to window.STC_PAGES
  const wrapper = `
(function() {
  const LangManager = {
    get currentLang() { return window.LangManager ? window.LangManager.currentLang : 'en'; },
    t: function(k) { return window.LangManager ? window.LangManager.t(k) : k; }
  };
  const t = (k) => LangManager.t(k);

${content}

  window.STC_PAGES = {
    homePage,
    partnersPage,
    partnerDetailPage,
    investorsPublicPage,
    blogsPage,
    blogDetailPage,
    eventsPage,
    eventDetailPage,
    jobsPage,
    jobDetailPage,
    branchesPage
  };
})();
`;

  fs.writeFileSync('./src/assets/js/public-pages.js', wrapper);
  console.log('public-pages.js generated successfully.');
} catch (e) {
  console.error('Error:', e);
}
