export interface TrainingSession {
  id?: number;
  branch_id: number;
  group?: number | null;
  day_of_week: string;
  start_time: string;
  end_time: string;
  start_date?: string;
  end_date?: string;
  is_active: boolean;
  max_capacity: number;
  notes?: string;
  branch?: any;
  players?: any[];
  created_at?: string;
  updated_at?: string;
}

export interface TrainingSessionFilters {
  branch_id?: number;
  group?: number;
  is_active?: boolean;
  day_of_week?: string;
  date?: string;
}


