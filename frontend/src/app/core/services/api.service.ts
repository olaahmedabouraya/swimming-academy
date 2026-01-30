import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
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
    
    // Use text response type to handle InfinityFree ad injection
    return this.http.post(url, body, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      responseType: 'text'
    }).pipe(
      map((responseText: string) => {
        // Check if response is HTML (InfinityFree ad injection)
        if (responseText.includes('<!doctype html>') || 
            responseText.includes('<html>') || 
            responseText.includes('aes.js') ||
            responseText.includes('This site requires Javascript')) {
          
          console.error('❌ InfinityFree is injecting HTML/ads into API response!');
          console.error('Attempting to extract JSON from HTML response...');
          
          // Try to extract JSON from the HTML response
          // Look for JSON objects in the response
          const jsonMatches = responseText.match(/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/g);
          if (jsonMatches && jsonMatches.length > 0) {
            // Try each potential JSON match
            for (const match of jsonMatches) {
              try {
                const jsonData = JSON.parse(match);
                // Check if it looks like our API response
                if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors) {
                  console.log('✅ Successfully extracted JSON from HTML response');
                  return jsonData as T;
                }
              } catch (e) {
                // Not valid JSON, continue
              }
            }
          }
          
          // If we can't extract JSON, throw an error
          throw new Error('Hosting service is injecting ads into API responses. The response was modified and could not be parsed.');
        }
        
        // Try to parse as JSON
        try {
          return JSON.parse(responseText) as T;
        } catch (e) {
          console.error('Failed to parse response as JSON:', responseText.substring(0, 200));
          throw new Error('Invalid JSON response from server');
        }
      }),
      catchError((error: any) => {
        console.error('API Error Details:', {
          status: error.status,
          statusText: error.statusText,
          url: error.url || url,
          message: error.message
        });
        
        // Check for CORS errors
        if (error.status === 0 || error.statusText === 'Unknown Error') {
          console.error('❌ CORS Error or Network Error!');
          console.error('Request URL:', url);
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

