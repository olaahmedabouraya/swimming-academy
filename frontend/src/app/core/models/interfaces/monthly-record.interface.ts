import { User } from './user.interface';

export interface MonthlyRecord {
  id?: number;
  branch_id: number;
  year: number;
  month: number;
  revenue: number;
  new_enrollments: number;
  total_active_players: number;
  selling_rate: number;
  total_sessions_conducted: number;
  total_attendance: number;
  notes?: string;
  created_by?: number;
  branch?: any; // Using any to avoid circular dependency
  creator?: User;
}

export interface MonthlyRecordFilters {
  branch_id?: number;
  year?: number;
  month?: number;
}

export interface MonthlyStatistics {
  total_revenue: number;
  total_enrollments: number;
  average_selling_rate: number;
  total_sessions: number;
  total_attendance: number;
}

