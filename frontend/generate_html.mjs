import './setup-mocks.js';
import fs from 'fs';
import { homePage } from './src/assets/js/pages/home.js';
import { loginPage, registerPage } from './src/assets/js/pages/auth.js';
import { investorDashboardPage } from './src/assets/js/pages/dashboard-investor.js';

try {
  // Generate Home HTML
  const homeHtml = homePage();
  fs.writeFileSync('./src/app/pages/home/home.component.html', homeHtml);

  // Generate Auth HTML (We will just put loginPage for now, but really AuthComponent needs to switch based on route)
  // Let's just put loginPage in AuthComponent for now to get it working
  const authHtml = loginPage();
  fs.writeFileSync('./src/app/pages/auth/auth.component.html', authHtml);

  // Generate Dashboard HTML
  const dashboardHtml = investorDashboardPage('overview');
  fs.writeFileSync('./src/app/pages/dashboard/dashboard.component.html', dashboardHtml);

  console.log('HTML successfully generated and injected into Angular components.');
} catch (e) {
  console.error('Error generating HTML:', e);
}
