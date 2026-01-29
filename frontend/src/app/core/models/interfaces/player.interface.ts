export interface Player {
  id?: number;
  user_id: number;
  branch_id?: number;
  level?: string;
  enrollment_date?: string;
  medical_notes?: string;
  emergency_contact?: string;
  current_session_id?: number;
  coach_id?: number;
  enrollment_type?: 'monthly' | 'per_session';
  period_start_date?: string;
  period_end_date?: string;
  sessions_per_month?: number;
  sessions_used?: number;
  excused_absences_allowed?: number;
  excused_absences_used?: number;
  sports_manager_notes?: string;
  user?: any;
  branch?: any;
  currentSession?: any;
  coach?: any;
  schedules?: any[];
  attendances?: any[];
  ratings?: any[];
  fees?: any[];
  familyRelationships?: any[];
  average_rating?: number;
  attendance_rate?: number;
  total_attendances?: number;
  created_at?: string;
  updated_at?: string;
}

export interface PlayerFilters {
  branch_id?: number;
  level?: string;
}
