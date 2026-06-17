import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { AuthComponent } from './pages/auth/auth.component';
import { DashboardComponent } from './pages/dashboard/dashboard.component';
import { DynamicComponent } from './pages/dynamic/dynamic.component';

const routes: Routes = [
  { path: '', component: DynamicComponent },
  { path: 'auth', component: AuthComponent },
  { path: 'login', component: AuthComponent },
  { path: 'register', component: AuthComponent },
  { path: 'dashboard', component: DashboardComponent },
  { path: 'partners', component: DynamicComponent },
  { path: 'partner/:id', component: DynamicComponent },
  { path: 'investors', component: DynamicComponent },
  { path: 'events', component: DynamicComponent },
  { path: 'event/:id', component: DynamicComponent },
  { path: 'blogs', component: DynamicComponent },
  { path: 'blog/:id', component: DynamicComponent },
  { path: 'jobs', component: DynamicComponent },
  { path: 'job/:id', component: DynamicComponent },
  { path: 'contact', component: DynamicComponent },
  { path: 'branches', component: DynamicComponent },
  { path: '**', redirectTo: '' }
];

@NgModule({
  imports: [RouterModule.forRoot(routes, { useHash: true })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
