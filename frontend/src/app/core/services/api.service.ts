import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { catchError, map, switchMap } from 'rxjs/operators';
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
    
    const postOpts = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      responseType: 'text' as const
    };
    return this.http.post(url, body, postOpts).pipe(
      switchMap((responseText: string) => {
        const isAdHtml = responseText.includes('<!doctype html>') ||
          responseText.includes('<html>') || responseText.includes('aes.js') ||
          responseText.includes('This site requires Javascript');
        if (isAdHtml && !url.includes('i=1')) {
          const retryUrl = url + (url.includes('?') ? '&i=1' : '?i=1');
          console.warn('Host injected HTML; retrying POST with', retryUrl);
          return this.http.post(retryUrl, body, postOpts).pipe(
            map((retryText: string) => this.parsePostResponseText<T>(retryText))
          );
        }
        return of(this.parsePostResponseText<T>(responseText));
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

  private parsePostResponseText<T>(responseText: string): T {
    const isAdHtml = responseText.includes('<!doctype html>') ||
      responseText.includes('<html>') || responseText.includes('aes.js') ||
      responseText.includes('This site requires Javascript');
    if (isAdHtml) {
      console.error('❌ Host is injecting HTML/ads into API response!');
      const scriptMatches = responseText.match(/<script[^>]*>([\s\S]*?)<\/script>/gi);
      if (scriptMatches) {
        for (const script of scriptMatches) {
          const jsonMatch = script.match(/\{[\s\S]*\}/);
          if (jsonMatch) {
            try {
              const jsonData = JSON.parse(jsonMatch[0]);
              if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors) return jsonData as T;
            } catch (e) { /* continue */ }
          }
        }
      }
      const jsonPatterns = [/\{"message"[\s\S]*?\}/, /\{"user"[\s\S]*?\}/, /\{"token"[\s\S]*?\}/, /\{"errors"[\s\S]*?\}/, /\{"data"[\s\S]*?\}/];
      for (const pattern of jsonPatterns) {
        const match = responseText.match(pattern);
        if (match) {
          try {
            const jsonData = JSON.parse(match[0]);
            if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) return jsonData as T;
          } catch (e) { /* continue */ }
        }
      }
      const jsonRegex = /\{(?:[^{}]|(?:\{[^{}]*\}))*\}/g;
      const allMatches = responseText.match(jsonRegex);
      if (allMatches) {
        for (const match of allMatches.sort((a, b) => b.length - a.length)) {
          try {
            const jsonData = JSON.parse(match);
            if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) return jsonData as T;
          } catch (e) { /* continue */ }
        }
      }
      const bodyMatch = responseText.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
      if (bodyMatch) {
        const bodyJsonMatch = bodyMatch[1].match(/\{[\s\S]{20,}\}/);
        if (bodyJsonMatch) {
          try {
            const jsonData = JSON.parse(bodyJsonMatch[0]);
            if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors) return jsonData as T;
          } catch (e) { /* continue */ }
        }
      }
      const beforeHtml = responseText.split(/<html|<!doctype/i)[0];
      if (beforeHtml.trim()) {
        const jsonMatch = beforeHtml.match(/\{[\s\S]*\}/);
        if (jsonMatch) {
          try {
            const jsonData = JSON.parse(jsonMatch[0]);
            if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) return jsonData as T;
          } catch (e) { /* continue */ }
        }
      }
      const afterHtml = responseText.split(/<\/html>/i).pop();
      if (afterHtml?.trim()) {
        const jsonMatch = afterHtml.match(/\{[\s\S]*\}/);
        if (jsonMatch) {
          try {
            const jsonData = JSON.parse(jsonMatch[0]);
            if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) return jsonData as T;
          } catch (e) { /* continue */ }
        }
      }
      let braceCount = 0, jsonStart = -1;
      for (let i = 0; i < responseText.length; i++) {
        if (responseText[i] === '{') { if (braceCount === 0) jsonStart = i; braceCount++; }
        else if (responseText[i] === '}') {
          braceCount--;
          if (braceCount === 0 && jsonStart !== -1) {
            try {
              const jsonData = JSON.parse(responseText.substring(jsonStart, i + 1));
              if (jsonData.message || jsonData.user || jsonData.token || jsonData.errors || jsonData.data) return jsonData as T;
            } catch (e) { /* continue */ }
            jsonStart = -1;
          }
        }
      }
      throw new Error('Hosting service is injecting ads into API responses. Use a backend host that does not inject ads (e.g. Render, Fly.io). See DEPLOYMENT_RENDER.md for an ad-free backend host.');
    }
    try {
      return JSON.parse(responseText) as T;
    } catch (e) {
      throw new Error('Invalid JSON response from server');
    }
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

