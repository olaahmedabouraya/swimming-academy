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
          console.error('Response length:', responseText.length);
          console.error('First 500 chars:', responseText.substring(0, 500));
          console.error('Attempting to extract JSON from HTML response...');
          
          // Strategy 1: Look for JSON in script tags
          const scriptMatches = responseText.match(/<script[^>]*>([\s\S]*?)<\/script>/gi);
          if (scriptMatches) {
            for (const script of scriptMatches) {
              const jsonMatch = script.match(/\{[\s\S]*\}/);
              if (jsonMatch) {
                try {
                  const jsonData = JSON.parse(jsonMatch[0]);
                  if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors) {
                    console.log('✅ Extracted JSON from script tag');
                    return jsonData as T;
                  }
                } catch (e) {
                  // Continue
                }
              }
            }
          }
          
          // Strategy 2: Look for JSON objects with common API response keys
          // Try to find JSON that starts with { and contains common API fields
          const jsonPatterns = [
            /\{"message"[\s\S]*?\}/,
            /\{"user"[\s\S]*?\}/,
            /\{"token"[\s\S]*?\}/,
            /\{"errors"[\s\S]*?\}/,
            /\{"data"[\s\S]*?\}/
          ];
          
          for (const pattern of jsonPatterns) {
            const match = responseText.match(pattern);
            if (match) {
              try {
                const jsonData = JSON.parse(match[0]);
                if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) {
                  console.log('✅ Extracted JSON using pattern matching');
                  return jsonData as T;
                }
              } catch (e) {
                // Continue
              }
            }
          }
          
          // Strategy 3: Find all JSON-like objects and try them
          // Use a more sophisticated regex that handles nested objects
          const jsonRegex = /\{(?:[^{}]|(?:\{[^{}]*\}))*\}/g;
          const allMatches = responseText.match(jsonRegex);
          if (allMatches) {
            // Sort by length (longer is more likely to be the actual response)
            const sortedMatches = allMatches.sort((a, b) => b.length - a.length);
            for (const match of sortedMatches) {
              try {
                const jsonData = JSON.parse(match);
                // Check if it looks like our API response
                if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) {
                  console.log('✅ Extracted JSON from nested object search');
                  return jsonData as T;
                }
              } catch (e) {
                // Continue
              }
            }
          }
          
          // Strategy 4: Try to find JSON between HTML tags (in body, div, etc.)
          const bodyMatch = responseText.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
          if (bodyMatch) {
            const bodyContent = bodyMatch[1];
            const bodyJsonMatch = bodyContent.match(/\{[\s\S]{20,}\}/);
            if (bodyJsonMatch) {
              try {
                const jsonData = JSON.parse(bodyJsonMatch[0]);
                if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors) {
                  console.log('✅ Extracted JSON from body content');
                  return jsonData as T;
                }
              } catch (e) {
                // Continue
              }
            }
          }
          
          // If we can't extract JSON, log the full response for debugging
          console.error('❌ Could not extract JSON from HTML response');
          console.error('Full response (first 2000 chars):', responseText.substring(0, 2000));
          throw new Error('Hosting service is injecting ads into API responses. The response was modified and could not be parsed.');
        }
        
        // Try to parse as JSON (normal case)
        try {
          return JSON.parse(responseText) as T;
        } catch (e) {
          console.error('Failed to parse response as JSON');
          console.error('Response (first 500 chars):', responseText.substring(0, 500));
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

