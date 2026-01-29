export interface Coach {
  id?: number;
  user_id: number;
  branch_id?: number;
  specialization?: string;
  hourly_rate: number;
  notes?: string;
  is_active: boolean;
  user?: any;
  branch?: any;
  players?: any[];
  created_at?: string;
  updated_at?: string;
}

export interface CoachFilters {
  branch_id?: number;
  is_active?: boolean;
}

export interface CoachStats {
  total_attendances: number;
  total_hours: number;
  total_salary: number;
  calculated_salary?: number;
  late_count: number;
}


