export interface Schedule {
  id?: number;
  player_id: number;
  branch_id: number;
  day_of_week: string;
  start_time: string;
  end_time: string;
  instructor_name?: string;
  notes?: string;
  player?: any; // Using any to avoid circular dependency
  branch?: any; // Using any to avoid circular dependency
}

export interface ScheduleFilters {
  player_id?: number;
  branch_id?: number;
}
