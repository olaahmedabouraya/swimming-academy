import { bootstrapApplication } from '@angular/platform-browser';
import { provideRouter } from '@angular/router';
import { provideHttpClient, withInterceptors } from '@angular/common/http';
import { provideAnimations } from '@angular/platform-browser/animations';
import { AppComponent } from './app/app.component';
import { routes } from './app/app.routes';
import { authInterceptor } from './app/core/interceptors/auth.interceptor';

bootstrapApplication(AppComponent, {
  providers: [
    provideRouter(routes),
    provideHttpClient(withInterceptors([authInterceptor])),
    provideAnimations()
  ]
}).catch(err => {
  console.error('Error bootstrapping application:', err);
  // Show error message in the DOM
  const errorDiv = document.createElement('div');
  errorDiv.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; background: #e74c3c; color: white; padding: 20px; text-align: center; z-index: 10000;';
  errorDiv.innerHTML = '<h2>Application Error</h2><p>Failed to load the application. Please check the console for details.</p>';
  document.body.appendChild(errorDiv);
  throw err;
});



