import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';
import { Branch } from '../models';

@Injectable({
  providedIn: 'root'
})
export class BranchService {
  private endpoint = '/branches';

  constructor(private apiService: ApiService) {}

  getAllBranches(): Observable<Branch[]> {
    return this.apiService.get<Branch[]>(this.endpoint);
  }

  getBranchById(id: number): Observable<Branch> {
    return this.apiService.get<Branch>(`${this.endpoint}/${id}`);
  }

  createBranch(branchData: Partial<Branch>): Observable<Branch> {
    return this.apiService.post<Branch>(this.endpoint, branchData);
  }

  updateBranch(id: number, branchData: Partial<Branch>): Observable<Branch> {
    return this.apiService.put<Branch>(`${this.endpoint}/${id}`, branchData);
  }

  deleteBranch(id: number): Observable<any> {
    return this.apiService.delete(`${this.endpoint}/${id}`);
  }
}

