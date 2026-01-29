export interface ExcusedSession {
  id?: number;
  player_id: number;
  original_attendance_id?: number;
  original_session_id?: number;
  original_date: string;
  excuse_reason: string;
  status: 'pending' | 'makeup_taken' | 'discounted' | 'expired';
  makeup_attendance_id?: number;
  makeup_session_id?: number;
  makeup_date?: string;
  discounted_from_fee: boolean;
  discounted_fee_id?: number;
  approved_by?: number;
  player?: any;
  originalSession?: any;
  makeupSession?: any;
  approvedBy?: any;
  created_at?: string;
  updated_at?: string;
}

export interface ExcusedSessionFilters {
  player_id?: number;
  status?: string;
  start_date?: string;
  end_date?: string;
}


