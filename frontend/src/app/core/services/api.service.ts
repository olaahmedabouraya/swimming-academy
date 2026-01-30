import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { catchError } from 'rxjs/operators';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  // Use environment API URL, or fallback to window variable if set
  private apiUrl = (typeof window !== 'undefined' && (window as any).__API_URL__) 
    ? (window as any).__API_URL__ 
    : environment.apiUrl;

  constructor(private http: HttpClient) {
    // Log API URL in development to help debug
    if (!environment.production) {
      console.log('API URL:', this.apiUrl);
    }
    // Warn if using placeholder URL
    if (this.apiUrl.includes('your-backend-url')) {
      console.error('⚠️ API URL is not configured! Using placeholder:', this.apiUrl);
      console.error('Please set API_URL environment variable in Vercel');
    }
  }

  get<T>(endpoint: string, params?: Record<string, any>): Observable<T> {
    let httpParams = new HttpParams();
    if (params) {
      Object.keys(params).forEach(key => {
        if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
          httpParams = httpParams.set(key, params[key].toString());
        }
      });
    }
    return this.http.get<T>(`${this.apiUrl}${endpoint}`, { params: httpParams });
  }

  post<T>(endpoint: string, body: any): Observable<T> {
    const url = `${this.apiUrl}${endpoint}`;
    console.log('POST request to:', url);
    console.log('Request body:', body);
    return this.http.post<T>(url, body, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      responseType: 'json' as 'json'
    }).pipe(
      // Add error handling to detect HTML responses
      catchError((error: any) => {
        console.error('API Error Details:', {
          status: error.status,
          statusText: error.statusText,
          url: error.url || url,
          error: error.error
        });
        
        // Check if response is HTML
        if (error.error) {
          const errorStr = typeof error.error === 'string' ? error.error : JSON.stringify(error.error);
          if (errorStr.includes('<!doctype html>') || errorStr.includes('This site requires Javascript')) {
            console.error('❌ Received HTML instead of JSON!');
            console.error('Request URL:', url);
            console.error('Current API URL:', this.apiUrl);
            console.error('Error response:', errorStr.substring(0, 500));
            console.error('Possible causes:');
            console.error('  1. API_URL in Vercel is wrong');
            console.error('  2. CORS issue - backend not allowing frontend domain');
            console.error('  3. Request is hitting frontend instead of backend');
            throw new Error('API returned HTML instead of JSON. Check API_URL and CORS configuration.');
          }
        }
        
        // Check for CORS errors
        if (error.status === 0 || error.statusText === 'Unknown Error') {
          console.error('❌ CORS Error or Network Error!');
          console.error('Request URL:', url);
          console.error('This usually means:');
          console.error('  1. CORS not configured on backend');
          console.error('  2. Backend is down');
          console.error('  3. Network connectivity issue');
        }
        
        throw error;
      })
    );
  }

  put<T>(endpoint: string, body: any): Observable<T> {
    return this.http.put<T>(`${this.apiUrl}${endpoint}`, body, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    });
  }

  patch<T>(endpoint: string, body: any): Observable<T> {
    return this.http.patch<T>(`${this.apiUrl}${endpoint}`, body);
  }

  delete<T>(endpoint: string): Observable<T> {
    return this.http.delete<T>(`${this.apiUrl}${endpoint}`);
  }
}

