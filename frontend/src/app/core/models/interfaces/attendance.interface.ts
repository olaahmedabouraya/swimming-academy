export interface Attendance {
  id?: number;
  player_id: number;
  schedule_id?: number;
  session_id?: number;
  attendance_date: string;
  status: 'present' | 'absent' | 'late' | 'excused';
  check_in_time?: string;
  check_out_time?: string;
  actual_start_time?: string;
  actual_end_time?: string;
  coach_notes?: string;
  notes?: string;
  player?: any;
  schedule?: any;
  session?: any;
  created_at?: string;
  updated_at?: string;
}

export interface AttendanceFilters {
  player_id?: number;
  session_id?: number;
  attendance_date?: string;
  date_from?: string;
  date_to?: string;
}
