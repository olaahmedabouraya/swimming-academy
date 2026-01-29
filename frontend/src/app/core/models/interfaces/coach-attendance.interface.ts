export interface CoachAttendance {
  id?: number;
  coach_id: number;
  session_id: number;
  attendance_date: string;
  scheduled_start_time: string;
  scheduled_end_time: string;
  actual_start_time?: string;
  actual_end_time?: string;
  is_late: boolean;
  late_minutes: number;
  notes?: string;
  hours_worked?: number;
  recorded_by: number;
  coach?: any;
  session?: any;
  recordedBy?: any;
  created_at?: string;
  updated_at?: string;
}

export interface CoachAttendanceFilters {
  coach_id?: number;
  session_id?: number;
  attendance_date?: string;
  start_date?: string;
  end_date?: string;
}


